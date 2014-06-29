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
                'type' => Type::STRING,
                'options' => [
                    'length' => 255,
                    'notnull' => false
                ]
            ],
            'uri' => [
                'type' => Type::STRING,
                'options' => [
                    'length' => 255,
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
                'foreignColumns' => [
                    'id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            ]
        ]
    ]
);