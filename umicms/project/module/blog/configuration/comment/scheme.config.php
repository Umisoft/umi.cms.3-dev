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

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/scheme/hierarchicCollection.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/scheme/active.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/scheme/recyclable.config.php',
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
                'type' => Type::DATETIME
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
            ]
        ],
        'constraints' => [
            'comment_to_author' => [
                'foreignTable' => 'blog_author',
                'columns' => [
                    'author_id' => []
                ],
                'foreignColumns' => [
                    'id' => []
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
                'foreignColumns' => [
                    'id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);