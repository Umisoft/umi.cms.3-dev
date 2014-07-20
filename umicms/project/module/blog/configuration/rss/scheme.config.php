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
        'name' => 'blog_rss_import_scenario',
        'columns' => [
            'category_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'rss_url' => [
                'type' => Type::STRING
            ]
        ],
        'indexes' => [
            'category' => [
                'columns' => [
                    'category_id' => []
                ]
            ]
        ],
        'constants' => [
            'scenario_to_category' => [
                'foreignTable' => 'blog_category',
                'columns' => [
                    'category_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);