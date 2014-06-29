<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема колонок, общая для всех коллекций, объекты которых имеют страницу на сайте.
 */
return [
    'columns' => [
        'slug'             => [
            'type' => Type::STRING
        ],
        'meta_title'       => [
            'type'    => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'meta_title_en'       => [
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
        'meta_keywords_en'       => [
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
        'meta_description_en' => [
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
        'h1_en'               => [
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
        ],
        'contents_en'     => [
            'type' => Type::TEXT
        ],
        'layout_id'        => [
            'type'    => Type::BIGINT,
            'options' => [
                'unsigned' => true,
                'notnull' => false
            ]
        ]
    ],
    'indexes' => [
        'slug' => [
            'columns' => [
                'slug' => []
            ]
        ]
    ]
];