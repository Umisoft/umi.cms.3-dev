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
use umicms\project\module\blog\model\object\BlogComment;
use umicms\project\module\blog\model\object\BlogPost;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/pageCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/userAssociated.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_author'
        ],
        'fields' => [
            BlogAuthor::FIELD_POSTS => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'blogPost',
                'targetField' => BlogPost::FIELD_AUTHOR,
                'readOnly' => true
            ],
            BlogAuthor::FIELD_COMMENTS_COUNT => [
                'type' => IField::TYPE_DELAYED,
                'columnName' => 'comments_count',
                'defaultValue' => 0,
                'dataType'     => 'integer',
                'formula'      => 'calculateCommentsCount',
                'readOnly'     => true
            ],
            BlogAuthor::FIELD_COMMENTS => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'blogComment',
                'targetField' => BlogComment::FIELD_AUTHOR,
                'readOnly' => true
            ],
            BlogAuthor::FIELD_POSTS_COUNT => [
                'type' => IField::TYPE_DELAYED,
                'columnName' => 'posts_count',
                'defaultValue' => 0,
                'dataType'     => 'integer',
                'formula'      => 'calculatePostsCount',
                'readOnly'     => true,
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'posts_count',
                        'defaultValue' => 0
                    ],
                    'en-US' => [
                        'columnName' => 'posts_count_en',
                        'defaultValue' => 0
                    ]
                ]
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
                    BlogAuthor::FIELD_POSTS => [],
                    BlogAuthor::FIELD_COMMENTS => [],
                    BlogAuthor::FIELD_COMMENTS_COUNT => [],
                    BlogAuthor::FIELD_POSTS_COUNT => []
                ]
            ]
        ]
    ]
);
