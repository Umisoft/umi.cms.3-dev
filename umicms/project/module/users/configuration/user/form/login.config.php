<?php

use umi\filter\IFilterFactory;
use umi\form\element\Password;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\model\object\RegisteredUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [
        RegisteredUser::FIELD_LOGIN => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_LOGIN,
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ]
        ],
        RegisteredUser::FIELD_PASSWORD => [
            'type' => Password::TYPE_NAME,
            'label' => RegisteredUser::FIELD_PASSWORD,
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
        ]
    ]
];