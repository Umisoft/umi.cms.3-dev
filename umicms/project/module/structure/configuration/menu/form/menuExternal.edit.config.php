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
use umicms\project\module\structure\api\object\MenuExternalItem;

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
                MenuExternalItem::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => MenuExternalItem::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => MenuExternalItem::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'settings' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'settings',
            'elements' => [
                MenuExternalItem::FIELD_RESOURCE_URL => [
                    'type' => Text::TYPE_NAME,
                    'label' => MenuExternalItem::FIELD_RESOURCE_URL,
                    'options' => [
                        'dataSource' => MenuExternalItem::FIELD_RESOURCE_URL
                    ],
                ]
            ]
        ]
    ]
];