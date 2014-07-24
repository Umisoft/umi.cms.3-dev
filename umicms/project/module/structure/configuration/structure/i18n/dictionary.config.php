<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\structure\model\object\StaticPage;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\project\module\structure\model\object\SystemPage;

return [
    'en-US' => [

        'collection:structure:displayName' => 'Structure',

        StructureElement::FIELD_COMPONENT_PATH => 'Handler component path',
        StructureElement::FIELD_COMPONENT_NAME => 'Handler component name',
        SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS => 'Skip page in breadcrumbs',
        StaticPage::FIELD_IN_MENU => 'Show in menu',
        StaticPage::FIELD_SUBMENU_STATE => 'Submenu state',
        'neverShown' => 'Submenu never shown',
        'currentShown' => 'Submenu shown, when it is the current page',
        'alwaysShown' => 'Submenu always shown',

        'type:base:displayName' => 'Page',
        'type:system:displayName' => 'System page',
        'type:static:displayName' => 'Static page',
        'type:static:createLabel' => 'Create page',
    ],

    'ru-RU' => [

        'collection:structure:displayName' => 'Структура',

        StructureElement::FIELD_COMPONENT_PATH => 'Путь компонента-обработчика',
        StructureElement::FIELD_COMPONENT_NAME => 'Имя компонента-обработчика',
        SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS => 'Пропускать в хлебных крошках',
        StaticPage::FIELD_IN_MENU => 'Отображать в меню',
        StaticPage::FIELD_SUBMENU_STATE => 'Статус отображения в меню',
        'neverShown' => 'Подменю никогда не развернуто',
        'currentShown' => 'Подменю развернуто, если в нем находится текущая страница',
        'alwaysShown' => 'Подменю всегда развернуто',

        'type:base:displayName' => 'Страница',
        'type:system:displayName' => 'Системная страница',
        'type:static:displayName' => 'Статичная страница',
        'type:static:createLabel' => 'Добавить страницу',
    ]
];
 