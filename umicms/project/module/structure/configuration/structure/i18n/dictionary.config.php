<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        'type:static:createLabel' => 'Добавить страницу',
    ]
];
 