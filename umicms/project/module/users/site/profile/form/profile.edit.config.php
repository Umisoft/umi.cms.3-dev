<?php

use umi\form\element\CSRF;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\model\object\RegisteredUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user' => 'collection.user',
            'collection' => 'collection',
            'project.site.users.profile' => 'project.site.users.profile',
            'form' => 'form'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [

        RegisteredUser::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_DISPLAY_NAME
            ]
        ],

        RegisteredUser::FIELD_EMAIL => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_EMAIL,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_EMAIL,
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ]
        ],

        RegisteredUser::FIELD_FIRST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_FIRST_NAME,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_FIRST_NAME
            ]
        ],
        RegisteredUser::FIELD_MIDDLE_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_MIDDLE_NAME,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_MIDDLE_NAME
            ]
        ],
        RegisteredUser::FIELD_LAST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_LAST_NAME,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_LAST_NAME
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