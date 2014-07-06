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

use umicms\orm\collection\ICmsPageCollection;
use umicms\orm\object\ICmsPage;
use umicms\project\module\search\model\object\SearchIndex;

/**
 * Публичный интерфейс для индексирования модулей CMS для поиска.
 */
class SearchIndexApi extends BaseSearchApi
{
    /**
     * Перестраивает хранимый поисковый индекс для отдельной коллекции.
     * @param ICmsPageCollection $collection
     */
    public function buildSiteIndex(ICmsPageCollection $collection)
    {
        $collectionRecords = $collection->select()
            ->fields($collection->getIndexablePropertyNames())
            ->getResult();

        $this->buildSiteIndexForObjects($collectionRecords->fetchAll());
    }

    /**
     * Переиндексирует отдельный набор объектов.
     * @param ICmsPage[] $objects
     */
    public function buildSiteIndexForObjects(array $objects)
    {
        $this->clearObjectsIndex($objects);

        /**
         * @var ICmsPage $object
         */
        foreach ($objects as $object) {
            $this->buildIndexForObject($object);
        }
    }

    /**
     * Извлекает из объекта текстовые данные, пригодные для помещения в поисковый индекс.
     * @param ICmsPage $object
     * @return string
     */
    public function extractSearchableContent(ICmsPage $object)
    {
        $content = '';
        /**
         * @var ICmsPageCollection $collection
         */
        $collection = $object->getCollection();

        foreach ($collection->getIndexablePropertyNames() as $propName) {
            $value = $object->getValue($propName);
            if (!is_string($value)) {
                continue;
            }
            $content .= " " . $this->filterSearchableText($value);
        }

        return trim($content);
    }

    /**
     * Очищает текст от разметки и прочей нетекстовой информации.
     * В отличие от {@see normalizeIndexString()}, не удаляет пунктуацию и не меняет регистр символов,
     * полученный текст можно использовать для цитирования, например, в результатах поиска.
     *
     * @param string $textRaw
     * @return string
     */
    public function filterSearchableText($textRaw)
    {
        $nobr = preg_replace('#<br\s*/?>#uim', ' ', $textRaw);

        return html_entity_decode(strip_tags($nobr));
    }

    /**
     * Очищает индексы для заданных объектов.
     * @param ICmsPage[] $objects
     */
    private function clearObjectsIndex($objects)
    {
        $objectsByGuid = [];

        foreach ($objects as $object) {
            $objectsByGuid[] = $object->guid;
        }

        $indexCollection = $this->getSiteIndexCollection();

        $deleter = $indexCollection
            ->select()
            ->fields([SearchIndex::FIELD_IDENTIFY])
            ->where(SearchIndex::FIELD_REF_GUID)
            ->in($objectsByGuid);

        foreach ($deleter as $object) {
            $indexCollection->delete($object);
        }
    }

    /**
     * Добавляет индексную запись для страницы.
     * @param ICmsPage $page
     */
    private function buildIndexForObject(ICmsPage $page)
    {
        $indexCollection = $this->getSiteIndexCollection();
        $newIndexRecord = $indexCollection->add();
        $newIndexRecord
            ->setValue(SearchIndex::FIELD_COLLECTION_NAME, $page->getCollectionName())
            ->setValue(SearchIndex::FIELD_REF_GUID, $page->guid)
            ->setValue(
                SearchIndex::FIELD_CONTENT,
                $this->normalizeIndexString($this->extractSearchableContent($page))
            );
    }

}
