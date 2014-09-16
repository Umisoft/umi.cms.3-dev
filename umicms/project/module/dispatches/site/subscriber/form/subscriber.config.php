<?php

use umi\filter\IFilterFactory;
use umi\form\element\Submit;
use umi\form\element\Text;
use umicms\project\module\dispatches\model\object\BaseSubscriber;

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
        BaseSubscriber::FIELD_EMAIL => [
            'type' => Text::TYPE_NAME,
            'label' => BaseSubscriber::FIELD_EMAIL,
            'options' => [
                'filters' => [
                ]
            ]
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Submit'
        ]
    ]
];