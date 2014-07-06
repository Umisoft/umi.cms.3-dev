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
use umicms\project\module\blog\model\object\BlogPost;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/pageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_post'
        ],
        'fields' => [
            BlogPost::FIELD_PUBLISH_TIME => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'publish_time'
            ],
            BlogPost::FIELD_PUBLISH_STATUS => [
                'type' => IField::TYPE_STRING,
                'mutator' => 'changeStatus',
                'columnName' => 'publish_status',
                'defaultValue' => BlogPost::POST_STATUS_DRAFT
            ],
            BlogPost::FIELD_ANNOUNCEMENT => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'announcement',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'announcement'],
                    'en-US' => ['columnName' => 'announcement_en']
                ]
            ],
            BlogPost::FIELD_SOURCE => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'source'
            ],
            BlogPost::FIELD_PAGE_CONTENTS_RAW => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents_raw',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents_raw'],
                    'en-US' => ['columnName' => 'contents_raw_en']
                ]
            ],
            BlogPost::FIELD_CATEGORY => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'category_id',
                'target' => 'blogCategory'
            ],
            BlogPost::FIELD_AUTHOR => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'author_id',
                'target' => 'blogAuthor'
            ],
            BlogPost::FIELD_TAGS => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'blogTag',
                'bridge' => 'blogPostTag',
                'relatedField' => 'blogPost',
                'targetField' => 'tag'
            ],
            BlogPost::FIELD_COMMENTS_COUNT => [
                'type' => IField::TYPE_COUNTER,
                'columnName' => 'comments_count'
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogPost',
                'fields' => [
                    BlogPost::FIELD_ANNOUNCEMENT => [],
                    BlogPost::FIELD_SOURCE => [],
                    BlogPost::FIELD_PAGE_CONTENTS_RAW => [],
                    BlogPost::FIELD_CATEGORY => [],
                    BlogPost::FIELD_TAGS => [],
                    BlogPost::FIELD_PUBLISH_TIME => [],
                    BlogPost::FIELD_PUBLISH_STATUS => [],
                    BlogPost::FIELD_COMMENTS_COUNT => [],
                    BlogPost::FIELD_AUTHOR => []
                ]
            ]
        ]
    ]
);