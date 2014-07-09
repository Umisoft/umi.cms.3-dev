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
    require CMS_PROJECT_DIR . '/configuration/model/scheme/hierarchicCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/scheme/active.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/scheme/recyclable.config.php',
    [
        'name' => 'blog_comment',
        'columns' => [
            'author_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'post_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'contents' => [
                'type' => Type::TEXT,
                'options' => [
                    'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
                ]
            ],
            'contents_en' => [
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
            'publish_time' => [
                'type' => Type::DATETIME
            ],
            'publish_status' => [
                'type' => Type::STRING,
                'options' => [
                    'length' => 50
                ]
            ],
        ],
        'indexes' => [
            'author' => [
                'columns' => [
                    'author_id' => []
                ]
            ],
            'post' => [
                'columns' => [
                    'post_id' => []
                ]
            ],
            'publish_status' => [
                'columns' => [
                    'publish_status' => []
                ]
            ]
        ],
        'constraints' => [
            'comment_to_author' => [
                'foreignTable' => 'blog_author',
                'columns' => [
                    'author_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'comment_to_post' => [
                'foreignTable' => 'blog_post',
                'columns' => [
                    'post_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);