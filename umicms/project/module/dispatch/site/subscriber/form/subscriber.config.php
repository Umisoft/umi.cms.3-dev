<?php

use umi\filter\IFilterFactory;
use umi\form\element\Submit;
use umi\form\element\Text;

return [

    'options' => [
        'dictionaries' => [
            'project.site.dispatch'
        ],
    ],
    'attributes' => [
        'method' => 'post'
    ],

    'elements' => [
        'email_subscribe' => [
            'type' => Text::TYPE_NAME,
            'label' => 'E-mail',
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