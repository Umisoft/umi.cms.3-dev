<?php

use umi\validation\IValidatorFactory;
use umi\form\element\Submit;
use umi\form\element\Hidden;
use umi\form\element\Text;
use umi\form\element\CSRF;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\Subscription;

return [

    'options' => [
        'dictionaries' => [
            'project.site.dispatches.subscriber'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [
        Subscription::FIELD_DISPATCH => [
            'type' => Hidden::TYPE_NAME,
            'label' => Subscription::FIELD_DISPATCH,
            'options' => [
                'dataSource' => Subscription::FIELD_DISPATCH
            ],
        ],
        Subscriber::FIELD_EMAIL => [
            'type' => Text::TYPE_NAME,
            'label' => Subscriber::FIELD_EMAIL,
            'options' => [
                'dataSource' => Subscription::FIELD_SUBSCRIBER . '.' . Subscriber::FIELD_EMAIL,
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ]
        ],
        Subscriber::FIELD_FIRST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => Subscriber::FIELD_FIRST_NAME,
            'options' => [
                'dataSource' => Subscription::FIELD_SUBSCRIBER . '.' . Subscriber::FIELD_FIRST_NAME,
            ]
        ],
        Subscriber::FIELD_MIDDLE_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => Subscriber::FIELD_MIDDLE_NAME,
            'options' => [
                'dataSource' => Subscription::FIELD_SUBSCRIBER . '.' . Subscriber::FIELD_MIDDLE_NAME,
            ]
        ],
        Subscriber::FIELD_LAST_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => Subscriber::FIELD_LAST_NAME,
            'options' => [
                'dataSource' => Subscription::FIELD_SUBSCRIBER . '.' . Subscriber::FIELD_LAST_NAME,
            ]
        ],

        'csrf' => [
            'type' => CSRF::TYPE_NAME
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Subscribe'
        ]
    ]
];