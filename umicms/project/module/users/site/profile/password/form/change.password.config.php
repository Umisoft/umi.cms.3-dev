<?php

use umi\filter\IFilterFactory;
use umi\form\element\CSRF;
use umi\form\element\Hidden;
use umi\form\element\Password;
use umi\form\element\Submit;
use umi\validation\IValidatorFactory;
use umicms\form\element\PasswordWithConfirmation;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\users\model\object\RegisteredUser;

return [

    'options' => [
        'dictionaries' => [
            'project.site.users.profile.password' => 'project.site.users.profile.password',
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
                ]
            ]
        ],

        'newPassword' => [
            'type' => Password::TYPE_NAME,
            'label' => 'New password',
            'options' => [
                'dataSource' => RegisteredUser::FIELD_PASSWORD,
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        BaseFormWidget::INPUT_REDIRECT_URL => [
            'type' => Hidden::TYPE_NAME
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