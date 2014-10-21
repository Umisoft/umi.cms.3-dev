<?php

use umi\validation\IValidatorFactory;
use umi\form\element\Submit;
use umi\form\element\Hidden;
use umi\form\element\Text;
use umi\form\element\Textarea;
use umi\form\element\CSRF;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\Unsubscription;

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
        Unsubscription::FIELD_DISPATCH => [
            'type' => Hidden::TYPE_NAME,
            'label' => Unsubscription::FIELD_DISPATCH,
            'options' => [
                'dataSource' => Unsubscription::FIELD_DISPATCH
            ],
        ],
        Unsubscription::FIELD_REASON => [
            'type' => Textarea::TYPE_NAME,
            'label' => Unsubscription::FIELD_REASON,
            'options' => [
                'dataSource' => Unsubscription::FIELD_REASON
            ],
        ],


        'csrf' => [
            'type' => CSRF::TYPE_NAME
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Unsubscribe'
        ]
    ]
];