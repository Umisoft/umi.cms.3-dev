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

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\stemming\IStemmingAware;
use umi\stemming\TStemmingAware;
use umicms\orm\collection\CmsCollection;

/**
 * Базовый класс для API поиска, индексации и проч. бизнес-логики, связанной с поиском.
 */
class BaseSearchApi implements ICollectionManagerAware, IStemmingAware
{
    use TCollectionManagerAware;
    use TStemmingAware;

    /**
     * Приводит текст к виду, пригодному для передачи в поисковый запрос.
     * @param string $searchString
     * @return string
     */
    public function normalizeSearchString($searchString)
    {
        $stringOriginal = trim(mb_strtoupper($searchString, 'utf-8'));
        $stringOriginal = $this->filterStopwords($stringOriginal);

        if (!preg_match_all('/[0-9A-ZА-Я_]+/u', $stringOriginal, $matches)) {
            return '';
        }

        $foundWords = $matches[0];

        $originalSearchPart = "(" . implode(' ', $foundWords) . ")";
        $stringNormalized = $originalSearchPart;
        $variationGroups = [];
        foreach ($foundWords as $match) {
            $baseForms = $this->getStemming()
                ->getBaseForm($match);
            if (($pos = array_search($match, $baseForms) !== false)) {
                unset($baseForms[$pos]);
            }
            if ($baseForms) {
                $variationGroups[] = $baseForms;
            }
        }
        $stringVariations = '';
        foreach ($variationGroups as $group) {
            $stringVariations .= count($group) > 1 ? '(' . implode(' ', $group) . ') ' : $group[0] . ' ';
        }
        $stringVariations = trim($stringVariations);
        if ($stringVariations !== $stringOriginal) {
            $stringNormalized .= ' (' . $stringVariations . ')';
        } else {
            $stringNormalized = $stringOriginal;
        }

        return $stringNormalized;
    }

    /**
     * Приводит текст к виду, пригодному для сохранения в поисковый индекс.
     * @param string $string
     * @return string
     */
    public function normalizeIndexString($string)
    {
        $string = html_entity_decode(strip_tags($string));
        $string = mb_strtoupper($string, 'utf-8');
        $string = trim($this->filterStopwords($string));
        $string = preg_replace('/\s+/u', ' ', $string);
        $string = preg_replace('/[^0-9A-ZА-Я_ -]/u', '', $string);
        $string = preg_replace('/\s+/u', ' ', $string);

        return $this->filterStopwords($string);
    }

    /**
     * Возвращает коллекцию для сайтовой индексации.
     * @return CmsCollection
     */
    protected function getSiteIndexCollection()
    {
        return $this->getCollectionManager()->getCollection('searchIndex');
    }

    /**
     * Отбрасывает из текста слова, не представляющие ценности для поиска.
     * @param string $string
     * @return string
     */
    protected function filterStopwords($string)
    {
        return preg_replace('/\b[АОУИВСБЯК]\b/u', '', $string);
    }
}
