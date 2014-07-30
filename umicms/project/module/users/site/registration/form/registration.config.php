<?php

use umi\filter\IFilterFactory;
use umi\form\element\CSRF;
use umi\form\element\Hidden;
use umi\form\element\Submit;
use umi\form\element\Text;
use umicms\form\element\Captcha;
use umicms\form\element\PasswordWithConfirmation;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\users\model\object\RegisteredUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection', 'project.site.users.registration', 'form'
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
                'dataSource' => RegisteredUser::FIELD_LOGIN
            ]
        ],

        RegisteredUser::FIELD_PASSWORD => [
            'type' => PasswordWithConfirmation::TYPE_NAME,
            'label' => RegisteredUser::FIELD_PASSWORD,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_PASSWORD
            ]
        ],

        RegisteredUser::FIELD_EMAIL => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_EMAIL,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_EMAIL
            ]
        ],

        RegisteredUser::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => RegisteredUser::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => RegisteredUser::FIELD_DISPLAY_NAME
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

        BaseFormWidget::INPUT_REDIRECT_URL => [
            'type' => Hidden::TYPE_NAME
        ],

        'captcha' => [
            'type' => Captcha::TYPE_NAME
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