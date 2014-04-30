<?php

use umi\filter\IFilterFactory;
use umi\form\element\Password;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\api\object\AuthorizedUser;

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
        AuthorizedUser::FIELD_LOGIN => [
            'type' => Text::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_LOGIN,
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ]
        ],
        AuthorizedUser::FIELD_PASSWORD => [
            'type' => Password::TYPE_NAME,
            'label' => AuthorizedUser::FIELD_PASSWORD,
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