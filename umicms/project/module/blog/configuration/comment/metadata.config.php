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
        BlogComment::FIELD_ACTIVE => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'active',
            'defaultValue' => 1
        ],
        BlogComment::FIELD_LOCKED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'locked',
            'readOnly' => true,
            'defaultValue' => 0
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
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        BlogComment::FIELD_POST => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'post_id',
            'target' => 'blogPost'
        ],
        BlogComment::FIELD_CONTENTS => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents'
        ],
        BlogComment::FIELD_PUBLISH_TIME => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'publish_time'
        ]
    ],
    'types' => [
        'base' => [
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
                BlogComment::FIELD_ACTIVE,
                BlogComment::FIELD_LOCKED,
                BlogComment::FIELD_TRASHED,
                BlogComment::FIELD_CREATED,
                BlogComment::FIELD_UPDATED,
                BlogComment::FIELD_DISPLAY_NAME,
                BlogComment::FIELD_POST,
                BlogComment::FIELD_CONTENTS,
                BlogComment::FIELD_PUBLISH_TIME
            ]
        ]
    ]
];
