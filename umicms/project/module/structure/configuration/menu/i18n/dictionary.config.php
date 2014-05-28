<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\structure\api\object\MenuInternalItem;

return [
    'en-US' => [

        MenuInternalItem::FIELD_COLLECTION_NAME_ITEM => 'Collection name',
        MenuInternalItem::FIELD_ITEM_ID => 'Item ID',

        'type:menu:displayName' => 'Menu',
        'type:internalItem:displayName' => 'Internal link',
        'type:externalItem:displayName' => 'External link',
    ],

    'ru-RU' => [

        MenuInternalItem::FIELD_COLLECTION_NAME_ITEM => 'Имя коллекции',
        MenuInternalItem::FIELD_ITEM_ID => 'Идентификатор элемента коллекции',

        'type:menu:displayName' => 'Меню',
        'type:internalItem:displayName' => 'Внутренняя ссылка',
        'type:externalItem:displayName' => 'Внешняя ссылка',
    ]
];
 