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
use umicms\project\Environment;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/scheme/collection.config.php',
    [
        'name' => 'blog_rss_tag',
        'columns' => [
            'scenario_id' => [
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
            'rss_import_scenario' => [
                'columns' => [
                    'scenario_id' => []
                ]
            ],
            'tag' => [
                'columns' => [
                    'tag_id' => []
                ]
            ]
        ],
        'constraints' => [
            'to_scenario' => [
                'foreignTable' => 'blog_rss_import_scenario',
                'columns' => [
                    'scenario_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'to_tag' => [
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