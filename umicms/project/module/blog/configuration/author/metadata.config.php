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
use umicms\project\module\blog\model\object\BlogAuthor;
use umicms\project\module\blog\model\object\BlogPost;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/pageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_author'
        ],
        'fields' => [
            BlogAuthor::FIELD_PROFILE => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'profile_id',
                'target' => 'user'
            ],

            BlogAuthor::FIELD_POSTS => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'blogPost',
                'targetField' => BlogPost::FIELD_AUTHOR,
                'readOnly' => true
            ],
            BlogAuthor::FIELD_COMMENTS_COUNT => [
                'type' => IField::TYPE_COUNTER,
                'columnName' => 'comments_count'
            ],
            BlogAuthor::FIELD_POSTS_COUNT => [
                'type' => IField::TYPE_COUNTER,
                'columnName' => 'posts_count'
            ],
            BlogAuthor::FIELD_PAGE_CONTENTS_RAW => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents_raw',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents_raw'],
                    'en-US' => ['columnName' => 'contents_raw_en']
                ]
            ],
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogAuthor',
                'fields' => [
                    BlogAuthor::FIELD_PAGE_CONTENTS_RAW => [],
                    BlogAuthor::FIELD_PROFILE => [],
                    BlogAuthor::FIELD_POSTS => [],
                    BlogAuthor::FIELD_COMMENTS_COUNT => [],
                    BlogAuthor::FIELD_POSTS_COUNT => []
                ]
            ]
        ]
    ]
);
