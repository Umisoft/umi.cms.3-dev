<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема таблицы для простой коллекции объектов
 */
return [
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
            'columns' => ['id']
        ],
        'guid' => [
            'type' => 'unique',
            'columns' => ['guid']
        ],
        'type' => [
            'columns' => ['type']
        ]
    ]
];