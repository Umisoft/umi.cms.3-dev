<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема таблицы для простой коллекции объектов
 */
return [
    'options' => [
        'engine' => 'InnoDB',
        'charset' => 'utf8',
        'collate' => 'utf8_unicode_ci'
    ],
    'columns' => [
        'id'           => [
            'type'    => Type::BIGINT,
            'options' => [
                'unsigned'      => true,
                'autoincrement' => true
            ]
        ],
        'guid'         => [
            'type'    => Type::GUID
        ],
        'type'         => [
            'type'    => Type::STRING
        ],
        'version'      => [
            'type'    => Type::BIGINT,
            'options' => [
                'unsigned' => true,
                'default'  => 1
            ]
        ],
        'display_name' => [
            'type'    => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'display_name_en' => [
            'type'    => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'created' => [
            'type' => Type::DATETIME,
            'options' => [
                'notnull' => false
            ]
        ],
        'updated' => [
            'type' => Type::DATETIME,
            'options' => [
                'notnull' => false
            ]
        ],
        'owner_id' => [
            'type' => Type::BIGINT,
            'options' => [
                'unsigned' => true,
                'notnull' => false
            ]
        ],
        'editor_id' => [
            'type' => Type::BIGINT,
            'options' => [
                'unsigned' => true,
                'notnull' => false
            ]
        ]
    ],
    'indexes' => [
        'primary' => [
            'type' => 'primary',
            'columns' => [
                'id' => []
            ]
        ],
        'guid' => [
            'type' => 'unique',
            'columns' => [
                'guid' => []
            ]
        ],
        'type' => [
            'columns' => [
                'type' => []
            ]
        ],
        'owner' => [
            'columns' => [
                'owner_id' => []
            ]
        ],
        'editor' => [
            'columns' => [
                'editor_id' => []
            ]
        ]
    ],
    'constraints' => [
        'editor_to_user' => [
            'foreignTable' => 'user',
            'columns' => [
                'editor_id' => []
            ],
            'foreignColumns' => [
                'id' => []
            ],
            'options' => [
                'onUpdate' => 'CASCADE',
                'onDelete' => 'SET NULL'
            ]
        ],
        'owner_to_user' => [
            'foreignTable' => 'user',
            'columns' => [
                'owner_id' => []
            ],
            'foreignColumns' => [
                'id' => []
            ],
            'options' => [
                'onUpdate' => 'CASCADE',
                'onDelete' => 'SET NULL'
            ]
        ]
    ]
];