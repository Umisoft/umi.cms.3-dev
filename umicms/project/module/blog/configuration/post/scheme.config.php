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
use umicms\project\Environment;
use umicms\project\module\blog\model\object\BlogPost;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/scheme/pageCollection.config.php',
    [
        'name' => 'blog_post',
        'columns' => [
            'publish_time' => [
                'type' => Type::DATETIME
            ],
            'publish_status' => [
                'type' => Type::STRING,
                'options' => [
                    'length' => 50,
                    'default' => BlogPost::POST_STATUS_DRAFT
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
            ]
        ],
        'indexes' => [
            'author' => [
                'columns' => [
                    'author_id' => []
                ]
            ],
            'publish_status' => [
                'columns' => [
                    'publish_status' => []
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
            ]
        ]
    ]
);