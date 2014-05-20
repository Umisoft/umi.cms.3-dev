<?php

use umi\filter\IFilterFactory;
use umi\form\element\Hidden;
use umi\form\element\Submit;
use umi\form\element\Text;
use umicms\form\element\PasswordWithConfirmation;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\users\api\object\AuthorizedUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection', 'project.site.users.regisration', 'form'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [

        AuthorizedUser::FIELD_LOGIN => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_LOGIN,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_LOGIN,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        AuthorizedUser::FIELD_PASSWORD => [
            'type' => PasswordWithConfirmation::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_PASSWORD,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_PASSWORD,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        AuthorizedUser::FIELD_EMAIL => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_EMAIL,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_EMAIL,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        AuthorizedUser::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_DISPLAY_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        AuthorizedUser::FIELD_FIRST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_FIRST_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_FIRST_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],
        AuthorizedUser::FIELD_MIDDLE_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_MIDDLE_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_MIDDLE_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],
        AuthorizedUser::FIELD_LAST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_LAST_NAME,
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_LAST_NAME,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        BaseFormWidget::INPUT_REDIRECT_URL => [
            'type' => Hidden::TYPE_NAME
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Save'
        ]
    ]
];