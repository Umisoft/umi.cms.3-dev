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
use umicms\project\module\users\object\AuthorizedUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                AuthorizedUser::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => AuthorizedUser::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => AuthorizedUser::FIELD_DISPLAY_NAME
                    ],
                ],
                AuthorizedUser::FIELD_LOGIN => [
                    'type' => Text::TYPE_NAME,
                    'label' => AuthorizedUser::FIELD_LOGIN,
                    'options' => [
                        'dataSource' => AuthorizedUser::FIELD_LOGIN
                    ],
                ],
                AuthorizedUser::FIELD_EMAIL => [
                    'type' => Text::TYPE_NAME,
                    'label' => AuthorizedUser::FIELD_EMAIL,
                    'options' => [
                        'dataSource' => AuthorizedUser::FIELD_EMAIL
                    ],
                ]
            ]
        ]
    ]
];