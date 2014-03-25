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
use umicms\project\module\users\object\Guest;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Guest::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Guest::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Guest::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ]
    ]
];