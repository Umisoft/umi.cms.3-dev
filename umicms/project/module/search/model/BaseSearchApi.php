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

use umi\i18n\ILocalesAware;
use umi\i18n\TLocalesAware;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\stemming\IStemmingAware;
use umi\stemming\TStemmingAware;
use umicms\project\module\search\model\collection\SearchIndexCollection;

/**
 * Базовый класс для API поиска, индексации и проч. бизнес-логики, связанной с поиском.
 */
class BaseSearchApi implements ICollectionManagerAware, IStemmingAware, ILocalesAware
{
    use TCollectionManagerAware;
    use TStemmingAware;
    use TLocalesAware;

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

        $stemming = $this->getStemming();
        $stringNormalized = '';

        foreach ($matches[0] as $match) {
            $partOfSpeech = $stemming->getPartOfSpeech($match);
            if (empty($partOfSpeech) || array_intersect($partOfSpeech, ['С', 'П', 'Г'])) {
                foreach ($stemming->getBaseForm($match) as $baseForm) {
                    $stringNormalized .= ' ' . $baseForm;
                }
            }
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
     * @return SearchIndexCollection
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
