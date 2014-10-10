<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\search\model\object\SearchIndex;

/**
 * Коллекция поисковых индексов
 *
 * @method CmsSelector|SearchIndex[] select() Возвращает селектор для выбора индексов.
 * @method SearchIndex get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает индекс
 * @method SearchIndex getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает индекс по id
 * @method SearchIndex add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает индекс
 */
class SearchIndexCollection extends CmsCollection
{
    /**
     * @var SearchIndex[] $indexes список индексов по guid'ам индексируемых объектов
     */
    protected $indexes = [];

    /**
     * Проверяет, сущетсвует ли индекс для объекта
     * @param ICmsObject $object
     * @return bool
     */
    public function hasIndexForObject(ICmsObject $object)
    {
        if (isset($this->indexes[$object->guid])) {
            return true;
        }

        $index = $this->select()
            ->where(SearchIndex::FIELD_REF_GUID)
            ->equals($object->guid)
            ->result()->fetch();

        if ($index instanceof SearchIndex) {
            $this->indexes[$object->guid] = $index;

            return true;
        }

        return false;
    }

    /**
     * Возвращает существующий индекс для объекта или создает новый
     * @param ICmsObject $object
     * @return SearchIndex
     */
    public function getIndexForObject(ICmsObject $object)
    {
        if ($this->hasIndexForObject($object)) {
            return $this->indexes[$object->guid];
        }

        $index = $this->add()
            ->setValue(SearchIndex::FIELD_REF_GUID, $object->guid)
            ->setValue(SearchIndex::FIELD_REF_COLLECTION_NAME, $object->getCollectionName());

        $this->indexes[$object->guid] = $index;

        return $index;
    }
}
 