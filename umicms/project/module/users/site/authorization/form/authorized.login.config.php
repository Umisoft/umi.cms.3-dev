<?php

use umi\filter\IFilterFactory;
use umi\form\element\Hidden;
use umi\form\element\Password;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\api\object\AuthorizedUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection', 'project.site.users.authorization'
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
        ],
        'referer' => [
            'type' => Hidden::TYPE_NAME
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Log in'
        ]
    ]
];