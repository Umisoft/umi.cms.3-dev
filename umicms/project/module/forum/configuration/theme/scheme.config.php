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
    require CMS_PROJECT_DIR . '/configuration/model/scheme/pageCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/scheme/recyclable.config.php',
    [
        'name' => 'forum_theme',
        'columns' => [
            'conference_id' => [
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
        ],
        'indexes' => [
            'conference' => [
                'columns' => [
                    'conference_id' => []
                ]
            ],
            'author' => [
                'columns' => [
                    'author_id' => []
                ]
            ]
        ],
        'constraints' => [
            'theme_to_conference' => [
                'foreignTable' => 'forum_conference',
                'columns' => [
                    'conference_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'theme_to_author' => [
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