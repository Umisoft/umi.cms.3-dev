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
use umicms\project\module\users\object\User;

return [

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [
                User::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => User::FIELD_DISPLAY_NAME
                    ],
                ],
                User::FIELD_LOGIN => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => User::FIELD_LOGIN
                    ],
                ],
                User::FIELD_EMAIL => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => User::FIELD_EMAIL
                    ],
                ]
            ]
        ]
    ]
];