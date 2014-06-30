<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\Environment;
use umicms\project\module\blog\model\object\BlogTag;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/pageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_tag'
        ],
        'fields' => [
            BlogTag::FIELD_POSTS => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'blogPost',
                'bridge' => 'blogPostTag',
                'relatedField' => 'tag',
                'targetField' => 'blogPost',
            ],
            BlogTag::FIELD_POSTS_COUNT => [
                'type' => IField::TYPE_COUNTER,
                'columnName' => 'posts_count'
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogTag',
                'fields' => [
                    BlogTag::FIELD_POSTS,
                    BlogTag::FIELD_POSTS_COUNT,
                ]
            ]
        ]
    ]
);
