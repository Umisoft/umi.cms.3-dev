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
    require CMS_PROJECT_DIR . '/configuration/model/scheme/recyclable.config.php',
    [
        'name' => 'forum_message',
        'columns' => [
            'theme_id' => [
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
            'author_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ]
        ],
        'indexes' => [
            'theme' => [
                'columns' => [
                    'theme_id' => []
                ]
            ],
            'author' => [
                'columns' => [
                    'author_id' => []
                ]
            ]
        ],
        'constraints' => [
            'message_to_theme' => [
                'foreignTable' => 'forum_theme',
                'columns' => [
                    'theme_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'message_to_author' => [
                'foreignTable' => 'forum_author',
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
