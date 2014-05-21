<?php

use Doctrine\DBAL\Types\Type;

return [
    'columns' => [
        'layout_id'        => [
            'type'    => Type::BIGINT,
            'options' => [
                'unsigned' => true,
                'notnull' => false
            ]
        ],
        'meta_title'       => [
            'type'    => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'meta_keywords'    => [
            'type'    => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'meta_description' => [
            'type'    => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'h1'               => [
            'type'    => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'contents'         => [
            'type' => Type::TEXT,
            'options' => [
                'notnull' => false
            ]
        ]
    ]
];