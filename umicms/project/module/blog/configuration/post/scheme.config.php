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

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/scheme/pageCollection.config.php',
    [
        'name' => 'blog_post',
        'columns' => [
            'publish_time' => [
                'type' => Type::DATETIME
            ],
            'status_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'announcement' => [
                'type' => Type::TEXT,
                'options' => [
                    'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
                ]
            ],
            'announcement_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
                ]
            ],
            'source' => [
                'type' => Type::TEXT,
                'options' => [
                    'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
                ]
            ],
            'contents_raw' => [
                'type' => Type::TEXT,
                'options' => [
                    'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
                ]
            ],
            'contents_raw_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
                ]
            ],
            'category_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'author_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'comments_count' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true,
                    'default' => 0
                ]
            ],
            'image' => [
                'type' => Type::STRING
            ]
        ],
        'indexes' => [
            'author' => [
                'columns' => [
                    'author_id' => []
                ]
            ],
            'status' => [
                'columns' => [
                    'status_id' => []
                ]
            ]
        ],
        'constraints' => [
            'post_to_author' => [
                'foreignTable' => 'blog_author',
                'columns' => [
                    'author_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'post_to_status' => [
                'foreignTable' => 'blog_post_status',
                'columns' => [
                    'status_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);