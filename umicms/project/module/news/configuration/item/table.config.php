<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use Doctrine\DBAL\Types\Type;

return [
    'name' => 'umi_news_item',
    'columns' => [
        'id' => [
            'type' => Type::BIGINT,
            'options' => [
                'unsigned' => true,
                'notnull' => true,
                'autoincrement' => true
            ]
        ],
        'guid' => [
            'type' => Type::GUID,
            'options' => [
                'notnull' => true
            ]
        ],
        'type' => [
            'type' => Type::STRING,
            'options' => [
                'notnull' => true
            ]
        ],
        'version' => [
            'type' => Type::BIGINT,
            'options' => [
                'unsigned' => true,
                'notnull' => true,
                'default' => 1
            ]
        ],
        'slug' => [
            'type' => Type::STRING,
            'options' => [
                'notnull' => true
            ]
        ],
        'display_name' => [
            'type' => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'display_name_en' => [
            'type' => Type::STRING,
            'options' => [
                'notnull' => false
            ]
        ],
        'trashed' => [
            'type' => Type::BOOLEAN,
            'options' => [
                'default' => 0
            ]
        ],
        'active' => [
            'type' => Type::BOOLEAN,
            'options' => [
                'default' => 1
            ]
        ],
        'created' => [
            'type' => Type::DATETIME
        ],
        'updated' => [
            'type' => Type::DATETIME
        ],
        'owner_id' => [
            'type' => Type::BIGINT,
            'options' => [
                'unsigned' => true
            ]
        ],
        'editor_id' => [
            'type' => Type::BIGINT,
            'options' => [
                'unsigned' => true
            ]
        ],

        'date' => [
            'type' => Type::DATETIME
        ],
        'contents' => [
            'type' => Type::TEXT
        ],
        'contents_en' => [
            'type' => Type::TEXT
        ],
        'announcement' => [
            'type' => Type::TEXT
        ],
        'announcement_en' => [
            'type' => Type::TEXT
        ]

    ],
    'indexes' => [

    ],
    'constraints' => [

    ],
    'options' => []
];