<?php

use umi\filter\IFilterFactory;
use umi\form\element\CSRF;
use umi\form\element\Hidden;
use umi\form\element\Password;
use umi\form\element\Submit;
use umi\validation\IValidatorFactory;
use umicms\form\element\PasswordWithConfirmation;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\users\api\object\AuthorizedUser;

return [

    'options' => [
        'dictionaries' => [
            'project.site.users.profile.password', 'collection.user', 'collection', 'form'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [

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
            ]
        ],

        'newPassword' => [
            'type' => PasswordWithConfirmation::TYPE_NAME,
            'label' => 'New password',
            'options' => [
                'dataSource' => AuthorizedUser::FIELD_PASSWORD,
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