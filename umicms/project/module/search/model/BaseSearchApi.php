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
        $searchString = html_entity_decode(strip_tags($searchString));
        $searchString = preg_replace('/\s+/u', ' ', $searchString);
        $searchString = trim(mb_strtoupper($searchString, 'utf-8'));
        $searchString = $this->filterStopWords($searchString);

        if (!preg_match_all('/[0-9A-ZА-Я_]+/u', $searchString, $matches)) {
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
     * Возвращает коллекцию для сайтовой индексации.
     * @return SearchIndexCollection
     */
    public function getSiteIndexCollection()
    {
        return $this->getCollectionManager()->getCollection('searchIndex');
    }

    /**
     * Отбрасывает из текста слова, не представляющие ценности для поиска.
     * @param string $string
     * @return string
     */
    protected function filterStopWords($string)
    {
        return preg_replace('/\b[АОУИВСБЯК]\b/u', '', $string);
    }
}
