<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Platforms\MySqlPlatform;
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
            'type'    => Type::STRING
        ],
        'meta_title_en'       => [
            'type'    => Type::STRING
        ],
        'meta_keywords'    => [
            'type'    => Type::STRING
        ],
        'meta_keywords_en'       => [
            'type'    => Type::STRING
        ],
        'meta_description' => [
            'type'    => Type::STRING
        ],
        'meta_description_en' => [
            'type'    => Type::STRING
        ],
        'h1'               => [
            'type'    => Type::STRING
        ],
        'h1_en'               => [
            'type'    => Type::STRING
        ],
        'contents'         => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
        ],
        'contents_en'     => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
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
        ],
        'layout' => [
            'columns' => [
                'layout_id' => []
            ]
        ]
    ],
    'constraints' => [
        'to_layout' => [
            'foreignTable' => 'layout',
            'columns' => [
                'layout_id' => []
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