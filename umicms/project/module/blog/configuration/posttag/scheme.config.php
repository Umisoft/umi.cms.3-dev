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

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/scheme/collection.config.php',
    [
        'name' => 'blog_blog_post_tags',
        'columns' => [
            'post_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'tag_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ]
        ],
        'indexes' => [
            'post' => [
                'columns' => [
                    'post_id' => []
                ]
            ],
            'tag' => [
                'columns' => [
                    'tag_id' => []
                ]
            ]
        ],
        'constraints' => [
            'post_to_tag' => [
                'foreignTable' => 'blog_post',
                'columns' => [
                    'post_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'tag_to_post' => [
                'foreignTable' => 'blog_tag',
                'columns' => [
                    'tag_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);