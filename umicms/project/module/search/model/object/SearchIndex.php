<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\model\object;

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsObject;

/**
 * Индексная запись поиска.
 *
 * @property string $contents текст для полнотекстового поиска
 * @property string $refGuid GUID объекта, на который указывает индекс
 * @property string $refCollectionName Имя коллекции, к которой относится индексированный объект
 */
class SearchIndex extends CmsObject implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Имя поля для индексированного контента
     */
    const FIELD_CONTENT = 'contents';
    /**
     * Ссылка на GUID проиндексированного объекта
     */
    const FIELD_REF_GUID = 'refGuid';
    /**
     * Имя коллекции, которой принадлежит проиндексированный объект
     */
    const FIELD_REF_COLLECTION_NAME = 'refCollectionName';
    /**
     * Когда был записан индекс
     */
    const FIELD_DATE_INDEXED = 'dateIndexed';

    /**
     * Генерирует отображаемое имя, если оно не было установлено.
     * @param string|null $localeId
     * @return bool
     */
    public function validateDisplayName($localeId = null)
    {
        if (!$this->getValue(self::FIELD_DISPLAY_NAME, $localeId)) {
            $value = 'Index for ' . $this->refCollectionName . '#' . $this->refGuid;
            if ($localeId) {
                $value .= '_' . $localeId;
            }
            $this->setValue(self::FIELD_DISPLAY_NAME, $value, $localeId);
        }

        return true;
    }

    /**
     * Возвращает проиндексированный объект
     * @return ICmsObject
     */
    public function getIndexedObject()
    {
        return $this->getCollectionManager()
            ->getCollection($this->refCollectionName)
            ->get($this->refGuid);
    }
}
