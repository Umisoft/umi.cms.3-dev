<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\orm\metadata\IObjectType;
use umi\validation\IValidatorFactory;
use umicms\project\module\blog\api\object\BlogBaseComment;
use umicms\project\module\blog\api\object\BlogBranchComment;
use umicms\project\module\blog\api\object\BlogComment;

return [
    'dataSource' => [
        'sourceName' => 'umi_blog_comment'
    ],
    'fields' => [
        BlogComment::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        BlogComment::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        BlogComment::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        BlogComment::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        BlogComment::FIELD_PARENT => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor' => 'getParent',
            'target' => 'blogComment',
            'readOnly' => true
        ],
        BlogComment::FIELD_MPATH => [
            'type' => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor' => 'getMaterializedPath',
            'readOnly' => true
        ],
        BlogComment::FIELD_SLUG => [
            'type' => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor' => 'getSlug',
            'readOnly' => true
        ],
        BlogComment::FIELD_URI => [
            'type' => IField::TYPE_URI,
            'columnName' => 'uri',
            'accessor' => 'getURI',
            'readOnly' => true
        ],
        BlogComment::FIELD_CHILD_COUNT => [
            'type' => IField::TYPE_COUNTER,
            'columnName' => 'child_count',
            'accessor' => 'getChildCount',
            'readOnly' => true,
            'defaultValue' => 0
        ],
        BlogComment::FIELD_ORDER => [
            'type' => IField::TYPE_ORDER,
            'columnName' => 'order',
            'accessor' => 'getOrder',
            'readOnly' => true
        ],
        BlogComment::FIELD_HIERARCHY_LEVEL => [
            'type' => IField::TYPE_LEVEL,
            'columnName' => 'level',
            'accessor' => 'getLevel',
            'readOnly' => true
        ],
        BlogComment::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BlogComment::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        BlogComment::FIELD_TRASHED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'trashed',
            'defaultValue' => 0,
            'readOnly' => true,
        ],
        BlogComment::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly' => true
        ],
        BlogComment::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly' => true
        ],
        BlogComment::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => [],
                IFilterFactory::TYPE_STRIP_TAGS => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        BlogComment::FIELD_ACTIVE => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'active',
            'defaultValue' => 1
        ],
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
        BlogComment::FIELD_CONTENTS => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents',
            'localizations' => [
                'ru-RU' => ['columnName' => 'contents'],
                'en-US' => ['columnName' => 'contents_en']
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
            'objectClass' => 'umicms\project\module\blog\api\object\BlogBaseComment',
            'fields' => [
                BlogBaseComment::FIELD_IDENTIFY,
                BlogBaseComment::FIELD_GUID,
                BlogBaseComment::FIELD_TYPE,
                BlogBaseComment::FIELD_VERSION,
                BlogBaseComment::FIELD_PARENT,
                BlogBaseComment::FIELD_MPATH,
                BlogBaseComment::FIELD_SLUG,
                BlogBaseComment::FIELD_URI,
                BlogBaseComment::FIELD_CHILD_COUNT,
                BlogBaseComment::FIELD_ORDER,
                BlogBaseComment::FIELD_HIERARCHY_LEVEL,
                BlogBaseComment::FIELD_OWNER,
                BlogBaseComment::FIELD_EDITOR,
                BlogBaseComment::FIELD_TRASHED,
                BlogBaseComment::FIELD_CREATED,
                BlogBaseComment::FIELD_UPDATED,
                BlogBaseComment::FIELD_DISPLAY_NAME,
                BlogBaseComment::FIELD_ACTIVE,
                BlogBaseComment::FIELD_POST,
            ]
        ],
        BlogBranchComment::TYPE => [
            'objectClass' => 'umicms\project\module\blog\api\object\BlogBranchComment',
            'fields' => [
                BlogBranchComment::FIELD_IDENTIFY,
                BlogBranchComment::FIELD_GUID,
                BlogBranchComment::FIELD_TYPE,
                BlogBranchComment::FIELD_VERSION,
                BlogBranchComment::FIELD_PARENT,
                BlogBranchComment::FIELD_MPATH,
                BlogBranchComment::FIELD_SLUG,
                BlogBranchComment::FIELD_URI,
                BlogBranchComment::FIELD_CHILD_COUNT,
                BlogBranchComment::FIELD_ORDER,
                BlogBranchComment::FIELD_HIERARCHY_LEVEL,
                BlogBranchComment::FIELD_OWNER,
                BlogBranchComment::FIELD_EDITOR,
                BlogBranchComment::FIELD_CREATED,
                BlogBranchComment::FIELD_UPDATED,
                BlogBranchComment::FIELD_DISPLAY_NAME,
                BlogBranchComment::FIELD_ACTIVE,
                BlogBranchComment::FIELD_POST,
                BlogBranchComment::FIELD_PUBLISH_TIME,
                BlogBranchComment::FIELD_CHILDREN
            ]
        ],
        BlogComment::TYPE => [
            'objectClass' => 'umicms\project\module\blog\api\object\BlogComment',
            'fields' => [
                BlogComment::FIELD_IDENTIFY,
                BlogComment::FIELD_GUID,
                BlogComment::FIELD_TYPE,
                BlogComment::FIELD_VERSION,
                BlogComment::FIELD_PARENT,
                BlogComment::FIELD_MPATH,
                BlogComment::FIELD_SLUG,
                BlogComment::FIELD_URI,
                BlogComment::FIELD_CHILD_COUNT,
                BlogComment::FIELD_ORDER,
                BlogComment::FIELD_HIERARCHY_LEVEL,
                BlogComment::FIELD_OWNER,
                BlogComment::FIELD_EDITOR,
                BlogComment::FIELD_TRASHED,
                BlogComment::FIELD_CREATED,
                BlogComment::FIELD_UPDATED,
                BlogComment::FIELD_DISPLAY_NAME,
                BlogComment::FIELD_ACTIVE,
                BlogComment::FIELD_AUTHOR,
                BlogComment::FIELD_POST,
                BlogComment::FIELD_CONTENTS,
                BlogComment::FIELD_PUBLISH_TIME,
                BlogComment::FIELD_PUBLISH_STATUS,
                BlogComment::FIELD_CHILDREN
            ]
        ]
    ]
];
