<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\search\api;

use umi\dbal\builder\ISelectBuilder;
use umi\dbal\cluster\IDbClusterAware;
use umi\dbal\cluster\TDbClusterAware;
use umi\event\IEventObservant;
use umi\event\TEventObservant;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\object\IObject;
use umi\orm\objectset\IObjectSet;
use umi\orm\selector\ISelector;
use umi\spl\config\TConfigSupport;
use umi\stemming\TStemmingAware;
use umicms\api\IPublicApi;
use umicms\project\module\search\adapter\BaseAdapter;
use umicms\project\module\search\highlight\Fragmenter;
use umicms\project\module\search\object\SearchIndex;
use utest\event\TEventSupport;

/**
 * Публичный интерфейс поиска по модулям CMS
 */
class SearchApi extends BaseSearchApi implements IPublicApi, IDbClusterAware, IEventObservant
{
    use TDbClusterAware;
    use TEventObservant;
    use TConfigSupport;

    /**
     * @var BaseAdapter $searchAdapter
     */
    protected $searchAdapter;

    /**
     * Ищет совпадения с запросом среди объектов модулей, зарегистрированных в системе.
     * @param string $searchString
     * @return IObjectSet
     */
    public function search($searchString)
    {
        $this->fireEvent('search.before', ['query' => $searchString]);
        $words = $this->normalizeSearchString($searchString);

        $search = $this->getCollectionManager()
            ->getCollection('searchIndex')
            ->select();

        /** @var $search ISelector */
        $selectBuilder = $this->buildQueryCondition($search->getSelectBuilder(), $words);
        $this->fireEvent('search.buildCondition', ['selectBuilder' => $selectBuilder]);
        $result = [];
        foreach ($search->getResult() as $searchResult) {
            /** @var $searchResult IObject */
            $result[] = $this->getCollectionManager()
                ->getCollection($searchResult->getValue(SearchIndex::FIELD_COLLECTION_NAME))
                ->get(
                    $searchResult->getValue(SearchIndex::FIELD_REF_GUID)
                );
        }
        ;
        $this->fireEvent(
            'search.after',
            [
                'query' => $searchString,
                'selectBuilder' => $selectBuilder,
                'result' => $result
            ]
        );
        return $result;
    }

    /**
     * Выделяет подстроку во всех возможных формах.
     * Возвращает текст с подстроками, выделенными сконфигурированными маркерами.
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
            $searchGroups[]= '(' . implode("|", $searchReWords) . ')';
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
     * @param string $query
     * @param string $content
     * @return Fragmenter
     */
    public function getResultFragmented($query, $content)
    {
        $searchReParts = $this->extractSearchRegexpForms($query, $content);
        $wordsRegexp = '(' . implode("|", $searchReParts) . ')';
        return new Fragmenter($content, $wordsRegexp);
    }

    /**
     * Собирает все возможные вариации искомого слова, встречающиеся в тексте.
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
                $matched = preg_match('/(' . $word . ')/ui', $text, $matchesTmp);
            } else {
                $matched = preg_match('/(\w+' . $word . '\w+)/ui', $text, $matchesTmp);
            }
            if ($matched) {
                if ($lastMatches == $matchesTmp) {
                    break;
                } else {
                    array_shift($foundWords);
                }
                $foundWords[] = $matchesTmp[1];
            }
            $lastMatches = $matchesTmp;
            $word = mb_substr($word, 0, mb_strlen($word, 'utf-8') - 1, 'utf-8');
        }
        return $foundWords;
    }

    /**
     * Формирует все возможные регулярные выражения для поиска по тексту.
     * @param string $word Слово для поиска
     * @param string $text Текст, в котором происходит поиск
     * @return array
     */
    protected function extractSearchRegexpForms($word, $text)
    {
        $baseForm = $this->getStemming()
            ->getCommonRoot($word);
        $foundWords = [$word];
        $foundWords = array_merge($foundWords, $this->collectPossibleMatches($word, $text, true));
        $foundWords = array_merge($foundWords, $this->collectPossibleMatches($word, $baseForm));
        $foundWords = array_unique($foundWords);
        usort($foundWords, function ($word1, $word2) {
            return mb_strlen($word2, 'utf-8') - mb_strlen($word1, 'utf-8');
        });

        $searchReParts = [];
        foreach ($foundWords as $foundWord) {
            if ($foundWord == $word) {
                $searchReParts[] = '[\.,:…«»"\';_-]*' . $foundWord . '[\.,:…«»"\';_-]*';
            } else {
                $searchReParts[] = '\w*' . $foundWord . '\w*';
            }
        }
        return $searchReParts;
    }

    /**
     * Собирает условие поиска в бд по полнотекстовому индексу, в зависимости от используемой бд.
     * @param ISelectBuilder $selectBuilder Построитель запросов, используемый для поиска
     * @param array $words Искомые слова
     * @return ISelectBuilder
     */
    protected function buildQueryCondition(ISelectBuilder $selectBuilder, $words)
    {
        //todo mysql/postgresql/sphinx adapters
        $selectBuilder = $selectBuilder
            ->where()
            ->expr(':match', 'AGAINST', ":against")
            ->bindExpression(':match', 'MATCH(`content`)')
            ->bindExpression(
                ':against',
                "(" . $this->getDbCluster()
                    ->getConnection()
                    ->quote($words) . " IN BOOLEAN MODE)"
            );
        return $selectBuilder;
    }

    /**
     * @return BaseAdapter
     */
    public function getSearchAdapter()
    {
        return $this->searchAdapter;
    }
}
