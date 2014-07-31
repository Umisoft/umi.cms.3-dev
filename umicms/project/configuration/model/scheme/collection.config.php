<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            'type'    => Type::GUID,
            'options' => [
                'notnull' => true
            ]
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
            'type'    => Type::STRING
        ],
        'display_name_en' => [
            'type'    => Type::STRING
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
            'foreignTable' => 'users_user',
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
            'foreignTable' => 'users_user',
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