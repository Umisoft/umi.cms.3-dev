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
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\blog\api\object\BlogPost;

return [
    'dataSource' => [
        'sourceName' => 'umi_blog_category'
    ],
    'fields' => [
        BlogCategory::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        BlogCategory::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        BlogCategory::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        BlogCategory::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        BlogCategory::FIELD_PARENT => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor' => 'getParent',
            'target' => 'blogCategory',
            'readOnly' => true
        ],
        BlogCategory::FIELD_MPATH => [
            'type' => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor' => 'getMaterializedPath',
            'readOnly' => true
        ],
        BlogCategory::FIELD_SLUG => [
            'type' => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor' => 'getSlug',
            'readOnly' => true
        ],
        BlogCategory::FIELD_URI => [
            'type' => IField::TYPE_URI,
            'columnName' => 'uri',
            'accessor' => 'getURI',
            'readOnly' => true
        ],
        BlogCategory::FIELD_CHILD_COUNT => [
            'type' => IField::TYPE_COUNTER,
            'columnName' => 'child_count',
            'accessor' => 'getChildCount',
            'readOnly' => true,
            'defaultValue' => 0
        ],
        BlogCategory::FIELD_ORDER => [
            'type' => IField::TYPE_ORDER,
            'columnName' => 'order',
            'accessor' => 'getOrder',
            'readOnly' => true
        ],
        BlogCategory::FIELD_HIERARCHY_LEVEL => [
            'type' => IField::TYPE_LEVEL,
            'columnName' => 'level',
            'accessor' => 'getLevel',
            'readOnly' => true
        ],
        BlogCategory::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        BlogCategory::FIELD_ACTIVE => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'active',
            'defaultValue' => 1
        ],
        BlogCategory::FIELD_TRASHED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'trashed',
            'defaultValue' => 0,
            'readOnly' => true,
        ],
        BlogCategory::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly' => true
        ],
        BlogCategory::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly' => true
        ],
        BlogCategory::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BlogCategory::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        BlogCategory::FIELD_PAGE_META_TITLE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_title'
        ],
        BlogCategory::FIELD_PAGE_META_KEYWORDS => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_keywords'
        ],
        BlogCategory::FIELD_PAGE_META_DESCRIPTION => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_description'
        ],
        BlogCategory::FIELD_PAGE_H1 => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'h1'
        ],
        BlogCategory::FIELD_PAGE_CONTENTS => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents'
        ],
        BlogCategory::FIELD_PAGE_LAYOUT => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target' => 'layout'
        ],
        BlogCategory::FIELD_POSTS => [
            'type' => IField::TYPE_HAS_MANY,
            'target' => 'blogPost',
            'targetField' => BlogPost::FIELD_CATEGORY,
            'readOnly' => true
        ],
        BlogCategory::FIELD_CHILDREN => [
            'type' => IField::TYPE_HAS_MANY,
            'target' => 'blogCategory',
            'targetField' => BlogCategory::FIELD_PARENT,
            'readOnly' => true
        ]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\blog\api\object\BlogCategory',
            'fields' => [
                BlogCategory::FIELD_IDENTIFY,
                BlogCategory::FIELD_GUID,
                BlogCategory::FIELD_TYPE,
                BlogCategory::FIELD_VERSION,
                BlogCategory::FIELD_PARENT,
                BlogCategory::FIELD_MPATH,
                BlogCategory::FIELD_SLUG,
                BlogCategory::FIELD_URI,
                BlogCategory::FIELD_CHILD_COUNT,
                BlogCategory::FIELD_ORDER,
                BlogCategory::FIELD_HIERARCHY_LEVEL,
                BlogCategory::FIELD_DISPLAY_NAME,
                BlogCategory::FIELD_ACTIVE,
                BlogCategory::FIELD_TRASHED,
                BlogCategory::FIELD_CREATED,
                BlogCategory::FIELD_UPDATED,
                BlogCategory::FIELD_PAGE_META_TITLE,
                BlogCategory::FIELD_PAGE_META_KEYWORDS,
                BlogCategory::FIELD_PAGE_META_DESCRIPTION,
                BlogCategory::FIELD_PAGE_H1,
                BlogCategory::FIELD_PAGE_CONTENTS,
                BlogCategory::FIELD_PAGE_LAYOUT,
                BlogCategory::FIELD_OWNER,
                BlogCategory::FIELD_EDITOR,
                BlogCategory::FIELD_POSTS,
                BlogCategory::FIELD_CHILDREN
            ]
        ]
    ]
];
