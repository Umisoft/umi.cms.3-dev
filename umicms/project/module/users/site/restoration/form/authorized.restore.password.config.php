<?php

use umi\filter\IFilterFactory;
use umi\form\element\CSRF;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\form\element\Captcha;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection', 'project.site.users.restoration', 'form'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [

        'loginOrEmail' => [
            'type' => Text::TYPE_NAME,
            'label' => 'Login or email',
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ]
        ],

        'captcha' => [
            'type' => Captcha::TYPE_NAME
        ],

        'csrf' => [
            'type' => CSRF::TYPE_NAME
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Send request'
        ]
    ]
];