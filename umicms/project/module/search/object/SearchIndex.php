<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\search\object;

use umicms\orm\object\CmsObject;

/**
 * Индексная запись поиска.
 *
 * @property string $content текст для полнотекстового поиска
 * @property string $refGuid GUID объекта, на который указывает индекс
 * @property string $collectionName Имя коллекции, к которой относится индексированный объект
 */
class SearchIndex extends CmsObject
{
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
    const FIELD_COLLECTION_NAME = 'collectionName';
    /**
     * Когда был записан индекс
     */
    const FIELD_DATE_INDEXED = 'dateIndexed';
}
