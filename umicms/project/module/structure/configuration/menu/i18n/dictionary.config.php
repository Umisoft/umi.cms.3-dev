<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\structure\model\object\MenuExternalItem;
use umicms\project\module\structure\model\object\MenuInternalItem;

return [
    'en-US' => [

        'collection:menu:displayName' => 'Menu',

        MenuInternalItem::FIELD_PAGE_RELATION => 'Page link',

        'type:menu:displayName' => 'Menu',
        'type:internalItem:displayName' => 'Internal link',
        'type:externalItem:displayName' => 'External link',
        'settings' => 'Settings',
        MenuExternalItem::FIELD_RESOURCE_URL => 'Resource URL'
    ],

    'ru-RU' => [

        'collection:menu:displayName' => 'Меню',

        MenuInternalItem::FIELD_PAGE_RELATION => 'Ссылка на страницу',

        'type:menu:displayName' => 'Меню',
        'type:internalItem:displayName' => 'Внутренняя ссылка',
        'type:externalItem:displayName' => 'Внешняя ссылка',
        'settings' => 'Настройки',
        MenuExternalItem::FIELD_RESOURCE_URL => 'Внешняя ссылка'
    ]
];
 