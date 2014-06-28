<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема таблицы для простой иерархической коллекции объектов
 */
return array_merge_recursive(
    require __DIR__ . '/collection.config.php',
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
            'uri' => [
                'type' => 'unique',
                'columns' => ['uri']
            ]
        ]
    ]
);