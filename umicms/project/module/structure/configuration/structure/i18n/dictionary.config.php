<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\module\structure\api\object\SystemPage;

return [
    'en-US' => [

        StructureElement::FIELD_COMPONENT_PATH => 'Handler component path',
        StructureElement::FIELD_COMPONENT_NAME => 'Handler component name',
        SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS => 'Skip page in breadcrumbs',

        'type:base:displayName' => 'Page',
        'type:system:displayName' => 'System page',
        'type:static:displayName' => 'Static page',
        'type:static:createLabel' => 'Create page',
    ],

    'ru-RU' => [

        StructureElement::FIELD_COMPONENT_PATH => 'Путь компонента-обработчика',
        StructureElement::FIELD_COMPONENT_NAME => 'Имя компонента-обработчика',
        SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS => 'Пропускать в хлебных крошках',

        'type:base:displayName' => 'Страница',
        'type:system:displayName' => 'Системная страница',
        'type:static:displayName' => 'Статичная страница',
        'type:static:createLabel' => 'страницу',
    ]
];
 