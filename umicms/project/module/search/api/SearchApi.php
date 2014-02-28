<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\search\api;

use umi\dbal\cluster\IDbClusterAware;
use umi\dbal\cluster\TDbClusterAware;
use umi\event\IEventObservant;
use umi\event\TEventObservant;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\selector\ISelector;
use umi\stemming\TStemmingAware;
use umicms\api\IPublicApi;
use umicms\base\object\ICmsObject;
use umicms\project\module\search\highlight\Fragmenter;
use utest\event\TEventSupport;

/**
 * Интерфейс поиска по модулям CMS
 */
class SearchApi extends BaseSearchApi implements IPublicApi, IDbClusterAware, IEventObservant
{
    use TDbClusterAware;
    use TEventObservant;

    /**
     * Настройка маркера начала подсветки найденных результатов
     * @var string $searchHighlightStart
     */
    protected $searchHighlightStart = '<mark>';

    /**
     * Настройка маркера конца подсветки найденных результатов
     * @var string $searchHighlightEnd
     */
    protected $searchHighlightEnd = '</mark>';

    /**
     * @param string $searchString
     * @return \umi\orm\objectset\IObjectSet
     */
    public function search($searchString)
    {
        $this->fireEvent('search.before', ['query' => $searchString]);
        $words = $this->normalizeSearchString($searchString);

        /** @var $search ISelector */
        $search = $this->getCollectionManager()
            ->getCollection('index')
            ->select()
            ->where('content')
            ->apply(
                $this->getDbCluster()
                    ->select()
                    ->where()
                    ->expr('MATCH(`content`)', 'AGAINST', "(:query) IN BOOLEAN MODE")
                    ->bindString(':query', $words)
                    ->end()
            );
        $result = $search->getResult();
        $this->fireEvent('search.after', ['query' => $searchString, 'result' => $result]);
        return $result;
    }

    /**
     * Выделение подстроки во всех возможных формах.
     * Возвращает весь текст, с подстроками выделенными сконфигурированными тегами
     * @param string $query
     * @param string $text
     * @return string
     */
    public function highlightResult($query, $text)
    {
        $searchReParts = $this->extractSearchRegexpWords($query, $text);
        $searchRe = '(' . implode("|", $searchReParts) . ')';
        $highlight = preg_replace(
            '/' . $searchRe . '/iu',
            $this->searchHighlightStart . '$1' . $this->searchHighlightEnd,
            $text
        );
        return $highlight;
    }

    /**
     * Выделение подстроки во всех возможных формах.
     * Возвращает фрагменты с выделенными результатами поиска
     * @param $query
     * @param string $content
     * @internal param $text
     * @return Fragmenter
     */
    public function getResultFragmented($query, $content)
    {
        $searchReParts = $this->extractSearchRegexpWords($query, $content);
        $wordsRegexp = '(' . implode("|", $searchReParts) . ')';
        return new Fragmenter($content, $wordsRegexp);
    }

    /**
     * @param $fragment
     * @param $text
     * @param bool $exact
     * @return array
     */
    protected function collectPossibleMatches($fragment, $text, $exact = false)
    {
        $foundWords = [$fragment];
        $lastMatches = [];
        while (mb_strlen($fragment, 'utf-8') > 2) {
            if ($exact) {
                $matched = preg_match('/(' . $fragment . ')/ui', $text, $matchesTmp);
            } else {
                $matched = preg_match('/(\w+' . $fragment . '\w+)/ui', $text, $matchesTmp);
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
            $fragment = mb_substr($fragment, 0, mb_strlen($fragment, 'utf-8') - 1, 'utf-8');
        }
        return $foundWords;
    }

    /**
     * @param $fragment
     * @param $text
     * @return array
     */
    protected function extractSearchRegexpWords($fragment, $text)
    {
        $baseForm = $this->getStemming()
            ->getCommonRoot($fragment);
        $foundWords = [$fragment];
        $foundWords = array_merge($foundWords, $this->collectPossibleMatches($fragment, $text, true));
        $foundWords = array_merge($foundWords, $this->collectPossibleMatches($fragment, $baseForm));
        $foundWords = array_unique($foundWords);

        $searchReParts = [];
        foreach ($foundWords as $word) {
            if ($word == $fragment) {
                $searchReParts[] = '[\.,:…«»"\';_-]*' . $word . '[\.,:…«»"\';_-]*';
            } else {
                $searchReParts[] = '\w*' . $word . '\w*';
            }
        }
        return $searchReParts;
    }

}
