<?php

use Doctrine\DBAL\Types\Type;
use umicms\project\Environment;

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/scheme/simple.config.php',
    [
        'columns' => [
            'parent_id' => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true,
                    'notnull' => false
                ]
            ],
            'mpath' => [
                'type' => Type::STRING
            ],
            'slug'             => [
                'type' => Type::STRING
            ],
            'uri' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'order' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true,
                    'notnull' => false
                ]
            ],
            'level' => [
                'type'    => Type::SMALLINT,
                'options' => [
                    'unsigned' => true,
                    'notnull' => false
                ]
            ],
            'child_count' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true,
                    'default' => 0
                ]
            ]
        ],
        'indexes' => [
            'parent_slug' => [
                'type' => 'unique',
                'columns' => ['parent_id', 'slug']
            ],
            'uri' => [
                'type' => 'unique',
                'columns' => ['uri']
            ]
        ]
    ]
);