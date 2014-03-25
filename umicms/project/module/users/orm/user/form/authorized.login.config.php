<?php

use umi\form\element\Password;
use umi\form\element\Text;
use umicms\project\module\users\object\AuthorizedUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection'
        ]
    ],

    'elements' => [
        AuthorizedUser::FIELD_LOGIN => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_LOGIN,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_LOGIN
            ],
        ],
        AuthorizedUser::FIELD_PASSWORD => [
            'type' => Password::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_PASSWORD,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_PASSWORD
            ],
        ]
    ]
];