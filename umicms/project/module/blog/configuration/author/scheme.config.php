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
        'name' => 'blog_author',
        'columns' => [
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
            'profile_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'comments_count' => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ],
            'posts_count' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ],
            'posts_count_en' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ]
        ],
        'indexes' => [
            'profile' => [
                'columns' => [
                    'profile_id' => []
                ]
            ]
        ],
        'constraints' => [
            'profile_to_author' => [
                'foreignTable' => 'users_user',
                'columns' => [
                    'profile_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);