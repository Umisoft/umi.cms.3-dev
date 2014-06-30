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
use umicms\project\Environment;
use umicms\project\module\blog\model\object\BlogBaseComment;
use umicms\project\module\blog\model\object\BlogBranchComment;
use umicms\project\module\blog\model\object\BlogComment;

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/hierarchicCollection.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/metadata/active.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/metadata/recyclable.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_comment'
        ],
        'fields' => [
            BlogComment::FIELD_AUTHOR => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'author_id',
                'target' => 'blogAuthor'
            ],
            BlogComment::FIELD_POST => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'post_id',
                'target' => 'blogPost'
            ],
            BlogComment::FIELD_CONTENTS_RAW => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents_raw'],
                    'en-US' => ['columnName' => 'contents_raw_en']
                ]
            ],
            BlogComment::FIELD_PUBLISH_TIME => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'publish_time'
            ],
            BlogComment::FIELD_PUBLISH_STATUS => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'publish_status'
            ],
            BlogComment::FIELD_CHILDREN => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'blogComment',
                'targetField' => BlogComment::FIELD_PARENT,
                'readOnly' => true
            ]
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogBaseComment',
                'fields' => [
                    BlogBaseComment::FIELD_POST
                ]
            ],
            BlogBranchComment::TYPE => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogBranchComment',
                'fields' => [
                    BlogBranchComment::FIELD_PUBLISH_TIME,
                    BlogBranchComment::FIELD_CHILDREN
                ]
            ],
            BlogComment::TYPE => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogComment',
                'fields' => [
                    BlogComment::FIELD_AUTHOR,
                    BlogComment::FIELD_CONTENTS,
                    BlogComment::FIELD_CONTENTS_RAW,
                    BlogComment::FIELD_PUBLISH_TIME,
                    BlogComment::FIELD_PUBLISH_STATUS,
                    BlogComment::FIELD_CHILDREN
                ]
            ]
        ]
    ]
);
