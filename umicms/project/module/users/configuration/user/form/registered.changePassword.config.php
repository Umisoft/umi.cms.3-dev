<?php

use umi\filter\IFilterFactory;
use umi\form\element\Password;
use umi\form\element\Submit;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\model\object\RegisteredUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user' => 'collection.user',
            'collection' => 'collection',
            'form' => 'form'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [

        RegisteredUser::FIELD_PASSWORD => [
            'type' => Password::TYPE_NAME,
            'label' => RegisteredUser::FIELD_PASSWORD,
            'options' => [
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        'newPassword' => [
            'type' => Password::TYPE_NAME,
            'label' => 'New password',
            'options' => [
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        'passwordConfirmation' => [
            'type' => Password::TYPE_NAME,
            'label' => 'Password Confirmation',
            'options' => [
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Save'
        ]
    ]
];