<?php

use umi\filter\IFilterFactory;
use umi\form\element\CSRF;
use umi\form\element\Submit;
use umi\form\element\Text;
use umicms\project\module\users\api\object\AuthorizedUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection', 'project.site.users.profile', 'form'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [

        AuthorizedUser::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_DISPLAY_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],

        AuthorizedUser::FIELD_EMAIL => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_EMAIL,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_EMAIL,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],

        AuthorizedUser::FIELD_FIRST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_FIRST_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_FIRST_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],
        AuthorizedUser::FIELD_MIDDLE_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_MIDDLE_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_MIDDLE_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],
        AuthorizedUser::FIELD_LAST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_LAST_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_LAST_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],

        'csrf' => [
            'type' => CSRF::TYPE_NAME
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Save'
        ]
    ]
];