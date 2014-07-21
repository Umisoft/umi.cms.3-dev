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
            ->fields([SearchIndex::FIELD_REF_COLLECTION_NAME, SearchIndex::FIELD_REF_GUID]);

        $this->buildQueryCondition(
            $selector,
            $this->normalizeSearchString($searchString)
        );

        return $selector;
    }

    /**
     * Выделяет подстроку во всех возможных формах.
     * Возвращает текст с подстроками, выделенными сконфигурированными маркерами.
     *
     * @param string $query исходный запрос
     * @param string $text текст, в котором нужно выделить найденные подстроки
     * @param string $highlightStart маркер начала выделения подстроки
     * @param string $highlightEnd маркер окончания выделения подстроки
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
     */
    protected function buildQueryCondition(CmsSelector $selector, $searchString)
    {
        $searchMetadata = $selector->getCollection()->getMetadata();

        $selector->setSelectBuilderInitializer(
            function(ISelectBuilder $selectBuilder) use ($searchMetadata, $searchString) {

                $contentColumnName = $searchMetadata->getField(SearchIndex::FIELD_CONTENT)->getColumnName($this->getCurrentDataLocale());

                $connection = $searchMetadata->getCollectionDataSource()->getConnection();

                $selectBuilder->where()
                    ->expr(':searchMatchExpression', '>', ':minimumSearchRelevance')
                    ->bindExpression(
                        ':searchMatchExpression',
                        'MATCH(' . $connection->quoteIdentifier($contentColumnName) . ') AGAINST (' . $connection->quote($searchString)
                        . ')'
                    )
                    ->bindInt(':minimumSearchRelevance', 0);
            }
        );
    }
}
