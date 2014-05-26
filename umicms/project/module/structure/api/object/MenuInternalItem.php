<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api\object;

use umicms\orm\object\TCmsObject;

/**
 * Класс описывающий пункт меню на внутренний ресурс.
 *
 * @property string $collectionNameItem имя коллекции
 * @property int $itemId идентификатор элемента коллекции
 */
class MenuInternalItem extends BaseMenu
{
    /**
     * Тип объекта
     */
    const TYPE = 'internalItem';
    /**
     *  Имя поля для хранения пути компонента-обработчика
     */
    const FIELD_COLLECTION_NAME_ITEM = 'collectionNameItem';
    /**
     *  Имя поля для хранения идентификатора элемента коллекции
     */
    const FIELD_ITEM_ID = 'itemId';
}
 