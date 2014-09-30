<?php

use umi\filter\IFilterFactory;
use umi\form\element\Submit;
use umi\form\element\Text;

return [

    'options' => [
        'dictionaries' => [
            'project.site.search' => 'project.site.search'
        ],
    ],
    'attributes' => [
        'method' => 'get'
    ],

    'elements' => [
        'query' => [
            'type' => Text::TYPE_NAME,
            'label' => 'Query',
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ]
            ]
        ],

        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Search'
        ]
    ]
];