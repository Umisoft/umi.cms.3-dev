<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема таблицы для простой иерархической коллекции объектов
 */
return array_replace_recursive(
    require __DIR__ . '/collection.config.php',
    [
        'columns' => [
            'parent_id' => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'mpath' => [
                'type' => Type::STRING,
                'options' => [
                    'length' => 255
                ]
            ],
            'slug'             => [
                'type' => Type::STRING
            ],
            'uri' => [
                'type' => Type::STRING,
                'options' => [
                    'length' => 255
                ]
            ],
            'order' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'level' => [
                'type'    => Type::SMALLINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'child_count' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ]
        ],
        'indexes' => [
            'slug' => [
                'columns' => [
                    'slug' => []
                ]
            ],
            'uri' => [
                'type' => 'unique',
                'columns' => [
                    'uri' => []
                ]
            ],
            'mpath' => [
                'type' => 'unique',
                'columns' => [
                    'mpath' => []
                ]
            ],
            'parent' => [
                'columns' => [
                    'parent_id' => []
                ]
            ]
        ],
        'constraints' => [
            'to_parent' => [
                'foreignTable' => '%self%',
                'columns' => [
                    'parent_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            ]
        ]
    ]
);