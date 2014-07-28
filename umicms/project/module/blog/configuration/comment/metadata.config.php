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
use umi\orm\metadata\IObjectType;
use umicms\project\module\blog\model\object\BlogBaseComment;
use umicms\project\module\blog\model\object\BlogBranchComment;
use umicms\project\module\blog\model\object\BlogComment;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/hierarchicCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/recyclable.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_comment'
        ],
        'fields' => [
            BlogBaseComment::FIELD_PUBLISH_TIME => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'publish_time',
            ],
            BlogBaseComment::FIELD_POST => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'post_id',
                'target' => 'blogPost',
                'mutator' => 'setPost'
            ],

            BlogComment::FIELD_AUTHOR => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'author_id',
                'target' => 'blogAuthor',
                'mutator' => 'setAuthor'
            ],
            BlogComment::FIELD_CONTENTS => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents'],
                    'en-US' => ['columnName' => 'contents']
                ]
            ],
            BlogComment::FIELD_CONTENTS_RAW => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents_raw',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents_raw'],
                    'en-US' => ['columnName' => 'contents_raw_en']
                ]
            ],
            BlogComment::FIELD_PUBLISH_STATUS => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'publish_status',
                'mutator' => 'setStatus'
            ]
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogBaseComment',
                'fields' => [
                    BlogBaseComment::FIELD_POST => []
                ]
            ],
            BlogBranchComment::TYPE => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogBranchComment',
                'fields' => [
                    BlogBranchComment::FIELD_PUBLISH_TIME => []
                ]
            ],
            BlogComment::TYPE => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogComment',
                'fields' => [
                    BlogComment::FIELD_AUTHOR => [],
                    BlogComment::FIELD_CONTENTS => [],
                    BlogComment::FIELD_CONTENTS_RAW => [],
                    BlogComment::FIELD_PUBLISH_TIME => [],
                    BlogComment::FIELD_PUBLISH_STATUS => []
                ]
            ]
        ]
    ]
);
