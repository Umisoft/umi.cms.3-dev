<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\structure\model\object\Menu;

return [

    'options' => [
        'dictionaries' => [
            'collection.menu', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Menu::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Menu::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Menu::FIELD_DISPLAY_NAME
                    ],
                ],
                Menu::FIELD_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Menu::FIELD_NAME,
                    'options' => [
                        'dataSource' => Menu::FIELD_NAME
                    ],
                ],
                Menu::FIELD_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => Menu::FIELD_SLUG,
                    'options' => [
                        'dataSource' => Menu::FIELD_SLUG
                    ],
                ]
            ]
        ]
    ]
];