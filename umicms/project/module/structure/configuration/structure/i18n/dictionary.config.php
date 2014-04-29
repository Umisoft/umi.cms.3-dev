<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\structure\api\object\StructureElement;

return [
    'en-US' => [

        StructureElement::FIELD_COMPONENT_PATH => 'Handler component path',
        StructureElement::FIELD_COMPONENT_NAME => 'Handler component name',

        'type:base:displayName' => 'Page',
        'type:system:displayName' => 'System page',
        'type:static:displayName' => 'Static page',
    ],

    'ru-RU' => [

        StructureElement::FIELD_COMPONENT_PATH => 'Путь компонента-обработчика',
        StructureElement::FIELD_COMPONENT_NAME => 'Имя компонента-обработчика',

        'type:base:displayName' => 'Страница',
        'type:system:displayName' => 'Системная страница',
        'type:static:displayName' => 'Статичная страница',
    ]
];
 