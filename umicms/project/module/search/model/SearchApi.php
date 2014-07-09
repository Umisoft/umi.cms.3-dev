<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\model;

use umi\dbal\builder\IExpressionGroup;
use umi\dbal\builder\ISelectBuilder;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\search\model\highlight\Fragmenter;
use umicms\project\module\search\model\object\SearchIndex;

/**
 * Публичный интерфейс поиска по модулям CMS
 */
class SearchApi extends BaseSearchApi
{
    /**
     * Минимальная длина слова в поисковом запросе.
     * Чем она больше, тем меньше нагрузка на поисковый движок.
     * @var int $minimumPhraseLength
     */
    public $minimumPhraseLength;
    /**
     * Минимальная длина пригодного к поиску корня слова.
     * Чем она больше, тем меньше будет найдено нерелевантных (двусмысленных) результатов
     * @var int $minimumWordRootLength
     */
    public $minimumWordRootLength;

    /**
     * Ищет совпадения с запросом среди объектов модулей, зарегистрированных в системе.
     * @param string $searchString
     * @return CmsSelector|SearchIndex[]
     */
    public function search($searchString)
    {
        if (mb_strlen($searchString, 'utf-8') < $this->minimumPhraseLength) {
            return $this->getSiteIndexCollection()->emptySelect();
        }

        $selector = $this->getSiteIndexCollection()
            ->select()
            ->fields([SearchIndex::FIELD_COLLECTION_NAME, SearchIndex::FIELD_REF_GUID]);

        $this->buildQueryCondition(
            $selector,
            $this->normalizeSearchString($searchString),
            $this->detectWordBases($searchString)
        );

        return $selector;
    }

    /**
     * Выделяет подстроку во всех возможных формах.
     * Возвращает текст с подстроками, выделенными сконфигурированными маркерами.
     *
     * @param string $query Исходный запрос
     * @param string $text Текст, в котором нужно выделить найденные подстроки
     * @param string $highlightStart Маркер начала выделения подстроки
     * @param string $highlightEnd Маркер окончания выделения подстроки
     * @return string
     */
    public function highlightResult($query, $text, $highlightStart, $highlightEnd)
    {
        $queryWords = preg_split('/\s+/u', $query);
        $searchGroups = [];
        foreach ($queryWords as $queryWord) {
            $searchReWords = $this->extractSearchRegexpForms($queryWord, $text);
            $searchGroups[] = '(' . implode("|", $searchReWords) . ')';
        }
        $searchRe = '(' . implode("|", $searchGroups) . ')+';
        $highlight = preg_replace(
            '/' . $searchRe . '/ium',
            $highlightStart . '$1' . $highlightEnd,
            $text
        );

        return $highlight;
    }

    /**
     * Выделение подстроки во всех возможных формах.
     * Возвращает коллекцию объектов-фрагментов (фрагментатор),
     * которую затем можно будет разбить на подстроки-фрагменты.
     *
     * @param string $query
     * @param string $content
     * @return Fragmenter
     */
    public function getResultFragmented($query, $content)
    {
        $searchReParts = $this->extractSearchRegexpForms($query, $content, false);
        $wordsRegexp = '(' . implode("|", $searchReParts) . ')';

        return new Fragmenter($content, $wordsRegexp);
    }

    /**
     * Собирает все возможные вариации искомого слова, встречающиеся в тексте.
     *
     * @param string $word Слово для поиска
     * @param string $text Текст, в котором происходит поиск
     * @param bool $exact Искать ли только точные совпадения
     * @return array
     */
    protected function collectPossibleMatches($word, $text, $exact = false)
    {
        $foundWords = [$word];
        $lastMatches = [];
        while (mb_strlen($word, 'utf-8') > 2) {
            if ($exact) {
                $matched = preg_match('/(' . $word . ')/ui', $text, $currentMatches);
            } else {
                $matched = preg_match('/(\w+' . $word . '\w+)/ui', $text, $currentMatches);
            }
            if ($matched) {
                if ($lastMatches == $currentMatches) {
                    break;
                } else {
                    array_shift($foundWords);
                }
                $foundWords[] = $currentMatches[1];
            }
            $lastMatches = $currentMatches;
            $word = mb_substr($word, 0, mb_strlen($word, 'utf-8') - 1, 'utf-8');
        }
        return $foundWords;
    }

    /**
     * Формирует все возможные регулярные выражения для поиска по тексту.
     *
     * @param string $word Слово для поиска
     * @param string $text Текст, в котором происходит поиск
     * @param bool $includeExact Добавить ли проверку на точное отдельное вхождение исходного слова
     * @return array
     */
    protected function extractSearchRegexpForms($word, $text, $includeExact = true)
    {
        $possibleWordBases = $this->getStemming()
            ->getBaseForm($word);

        $root = $this->getStemming()
            ->getSearchableRoot($word, 3);
        $foundWords = [$word];
        if ($includeExact) {
            $foundWords = array_merge($foundWords, $this->collectPossibleMatches($word, $text, true));
        }
        $foundWords = array_merge($foundWords, $this->collectPossibleMatches($word, $root));

        $foundWords = array_filter(
            array_unique($foundWords),
            function ($foundWord) use ($possibleWordBases) {
                $possibleFoundBases = $this->getStemming()
                    ->getBaseForm($foundWord);
                return count(array_intersect($possibleWordBases, $possibleFoundBases)) > 0;
            }
        );
        array_push($foundWords, $root);
        usort(
            $foundWords,
            function ($word1, $word2) {
                return mb_strlen($word2, 'utf-8') - mb_strlen($word1, 'utf-8');
            }
        );

        $searchReParts = [];
        foreach ($foundWords as $foundWord) {
            if ($includeExact && ($foundWord == $word)) {
                $searchReParts[] = '[[:punct:]]*' . $foundWord . '[[:punct:]]*';
            } else {
                $searchReParts[] = '[[:punct:][:word:]]*' . $foundWord . '[[:punct:][:word:]]*';
            }
        }
        return $searchReParts;
    }

    /**
     * Собирает условие поиска в бд по полнотекстовому индексу, в зависимости от используемой бд.
     * Позволяет модифицировать это условие после формирования, с помощью подписки на событие search.buildCondition.
     * @param CmsSelector $selector
     * @param string $searchString искомые слова
     * @param array $wordBases базовые формы искомых слов для второстепенных совпадений
     */
    protected function buildQueryCondition(CmsSelector $selector, $searchString, array $wordBases)
    {
        $searchMetadata = $selector->getCollection()->getMetadata();

        $selector->setSelectBuilderInitializer(
            function(ISelectBuilder $selectBuilder) use ($searchMetadata, $searchString, $wordBases) {

                $contentColumnName = $searchMetadata->getField(SearchIndex::FIELD_CONTENT)->getColumnName($this->getCurrentDataLocale());

                $connection = $searchMetadata->getCollectionDataSource()->getConnection();

                $selectBuilder->where(IExpressionGroup::MODE_OR)
                    ->expr(':searchMatchExpression', '>', ':minimumSearchRelevance')
                    ->bindExpression(
                        ':searchMatchExpression',
                        'MATCH(' . $connection->quoteIdentifier($contentColumnName) . ') AGAINST (' . $connection->quote($searchString)
                        . '  WITH QUERY EXPANSION)'
                    )
                    ->bindInt(':minimumSearchRelevance', 0);

                if (count($wordBases)) {
                    $selectBuilder->begin(IExpressionGroup::MODE_OR)
                        ->expr($contentColumnName, 'LIKE', ":searchLikeCondition")
                        ->end()
                        ->bindString(':searchLikeCondition', "%" . $this->buildLikeQueryPart($wordBases) . "%");
                }
            }
        );
    }

    /**
     * Преобразует слова фразы к их базовым формам, например "масло" » "масл"
     * Числа остаются без изменений, слишком короткие корни отбрасываются.
     * @param string $phrase
     * @return array
     */
    private function detectWordBases($phrase)
    {
        $bases = [];
        $parts = preg_split('/[\s_-]+/u', $phrase);

        foreach ($parts as &$part) {
            $partBase = $this->getStemming()->getCommonRoot($part);
            if (is_numeric($partBase) || (mb_strlen($partBase) > $this->minimumWordRootLength)) {
                $bases[] = $partBase;
            }
        }

        return $bases;
    }

    /**
     * Собирает часть проверочного выражения LIKE
     * @param array $wordBases
     * @return string
     */
    protected function buildLikeQueryPart(array $wordBases)
    {
        return implode('%', $wordBases);
    }
}
