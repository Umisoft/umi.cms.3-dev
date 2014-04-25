<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\module\blog\api\object\BlogPost;

return [
    'dataSource' => [
        'sourceName' => 'umi_blog_post'
    ],
    'fields' => [
        BlogPost::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        BlogPost::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        BlogPost::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        BlogPost::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        BlogPost::FIELD_PAGE_SLUG => [
            'type' => IField::TYPE_SLUG,
            'columnName' => 'slug',
        ],
        BlogPost::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        BlogPost::FIELD_ACTIVE => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'active',
            'defaultValue' => 1
        ],
        BlogPost::FIELD_TRASHED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'trashed',
            'defaultValue' => 0,
            'readOnly' => true,
        ],
        BlogPost::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly' => true
        ],
        BlogPost::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly' => true
        ],
        BlogPost::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BlogPost::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        BlogPost::FIELD_PUBLISH_TIME => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'publish_time'
        ],
        BlogPost::FIELD_PAGE_META_TITLE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_title'
        ],
        BlogPost::FIELD_PAGE_META_KEYWORDS => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_keywords'
        ],
        BlogPost::FIELD_PAGE_META_DESCRIPTION => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_description'
        ],
        BlogPost::FIELD_PAGE_H1 => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'h1'
        ],
        BlogPost::FIELD_ANNOUNCEMENT => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'announcement'
        ],
        BlogPost::FIELD_SOURCE => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'source'
        ],
        BlogPost::FIELD_PAGE_CONTENTS => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents'
        ],
        BlogPost::FIELD_PAGE_LAYOUT => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target' => 'layout'
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
            'objectClass' => 'umicms\project\module\blog\api\object\BlogPost',
            'fields' => [
                BlogPost::FIELD_IDENTIFY,
                BlogPost::FIELD_GUID,
                BlogPost::FIELD_TYPE,
                BlogPost::FIELD_VERSION,
                BlogPost::FIELD_DISPLAY_NAME,
                BlogPost::FIELD_ACTIVE,
                BlogPost::FIELD_TRASHED,
                BlogPost::FIELD_CREATED,
                BlogPost::FIELD_UPDATED,
                BlogPost::FIELD_OWNER,
                BlogPost::FIELD_EDITOR,
                BlogPost::FIELD_PAGE_H1,
                BlogPost::FIELD_PAGE_META_TITLE,
                BlogPost::FIELD_PAGE_META_KEYWORDS,
                BlogPost::FIELD_PAGE_META_DESCRIPTION,
                BlogPost::FIELD_PAGE_LAYOUT,
                BlogPost::FIELD_PAGE_SLUG,
                BlogPost::FIELD_ANNOUNCEMENT,
                BlogPost::FIELD_PAGE_CONTENTS,
                BlogPost::FIELD_CATEGORY,
                BlogPost::FIELD_TAGS,
                BlogPost::FIELD_PUBLISH_TIME,
                BlogPost::FIELD_COMMENTS_COUNT,
                BlogPost::FIELD_AUTHOR
            ]
        ]
    ]
];
