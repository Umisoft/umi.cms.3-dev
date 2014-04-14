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
use umicms\project\module\blog\api\object\BlogTag;
use umicms\project\module\blog\api\object\RssImportPost;

return [
    'dataSource' => [
        'sourceName' => 'umi_blog_tag'
    ],
    'fields' => [
        BlogTag::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        BlogTag::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        BlogTag::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        BlogTag::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        BlogTag::FIELD_PAGE_SLUG => [
            'type' => IField::TYPE_SLUG,
            'columnName' => 'slug'
        ],
        BlogTag::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        BlogTag::FIELD_ACTIVE => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'active',
            'defaultValue' => 1
        ],
        BlogTag::FIELD_LOCKED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'locked',
            'readOnly' => true,
            'defaultValue' => 0
        ],
        BlogTag::FIELD_TRASHED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'trashed',
            'defaultValue' => 0,
            'readOnly' => true,
        ],
        BlogTag::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly' => true
        ],
        BlogTag::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly' => true
        ],
        BlogTag::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BlogTag::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        BlogTag::FIELD_PAGE_META_TITLE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_title'
        ],
        BlogTag::FIELD_PAGE_META_KEYWORDS => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_keywords'
        ],
        BlogTag::FIELD_PAGE_META_DESCRIPTION => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_description'
        ],
        BlogTag::FIELD_PAGE_H1 => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'h1'
        ],
        BlogTag::FIELD_PAGE_CONTENTS => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents'
        ],
        BlogTag::FIELD_PAGE_LAYOUT => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target' => 'layout'
        ],
        BlogTag::FIELD_POSTS => [
            'type' => IField::TYPE_MANY_TO_MANY,
            'target' => 'blogPost',
            'bridge' => 'blogPostTag',
            'relatedField' => 'tag',
            'targetField' => 'blogPost',
        ]
        /*BlogTag::FIELD_RSS => [
            'type' => IField::TYPE_MANY_TO_MANY,
            'target' => 'rssImportPost',
            'bridge' => 'rssPostTag',
            'relatedField' => RssImportPost::FIELD_TAGS,
            'targetField' => 'rssImportPost',
        ]*/
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\blog\api\object\BlogTag',
            'fields' => [
                BlogTag::FIELD_IDENTIFY,
                BlogTag::FIELD_GUID,
                BlogTag::FIELD_TYPE,
                BlogTag::FIELD_VERSION,
                BlogTag::FIELD_DISPLAY_NAME,
                BlogTag::FIELD_ACTIVE,
                BlogTag::FIELD_LOCKED,
                BlogTag::FIELD_TRASHED,
                BlogTag::FIELD_CREATED,
                BlogTag::FIELD_UPDATED,
                BlogTag::FIELD_PAGE_META_TITLE,
                BlogTag::FIELD_PAGE_META_KEYWORDS,
                BlogTag::FIELD_PAGE_META_DESCRIPTION,
                BlogTag::FIELD_PAGE_H1,
                BlogTag::FIELD_PAGE_CONTENTS,
                BlogTag::FIELD_PAGE_LAYOUT,
                BlogTag::FIELD_PAGE_SLUG,
                BlogTag::FIELD_POSTS,
                //BlogTag::FIELD_RSS,
                BlogTag::FIELD_OWNER,
                BlogTag::FIELD_EDITOR
            ]
        ]
    ]
];