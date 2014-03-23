<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\metadata\field\IField;
use umi\orm\object\IHierarchicObject;
use umicms\orm\object\CmsObject;

return [
    'dataSource' => [
        'sourceName' => 'umi_blog_comment'
    ],
    'fields'     => [

        IHierarchicObject::FIELD_IDENTIFY        => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId'
        ],
        IHierarchicObject::FIELD_GUID            => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'mutator'    => 'setGuid'
        ],
        IHierarchicObject::FIELD_TYPE            => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        IHierarchicObject::FIELD_VERSION         => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'mutator'      => 'setVersion',
            'defaultValue' => 1
        ],
        IHierarchicObject::FIELD_PARENT          => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor'   => 'getParent',
            'target'     => 'blogComment',
            'readOnly'   => true
        ],
        IHierarchicObject::FIELD_MPATH           => [
            'type'       => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor'   => 'getMaterializedPath',
            'readOnly'   => true
        ],
        IHierarchicObject::FIELD_SLUG            => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor'   => 'getSlug',
            'readOnly'   => true
        ],
        IHierarchicObject::FIELD_URI             => [
            'type'       => IField::TYPE_URI,
            'columnName' => 'uri',
            'accessor'   => 'getURI',
            'readOnly'   => true
        ],
        IHierarchicObject::FIELD_CHILD_COUNT     => [
            'type'         => IField::TYPE_COUNTER,
            'columnName'   => 'child_count',
            'accessor'     => 'getChildCount',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        IHierarchicObject::FIELD_ORDER           => [
            'type'       => IField::TYPE_ORDER,
            'columnName' => 'order',
            'accessor'   => 'getOrder',
            'readOnly'   => true
        ],
        IHierarchicObject::FIELD_HIERARCHY_LEVEL => [
            'type'       => IField::TYPE_LEVEL,
            'columnName' => 'level',
            'accessor'   => 'getLevel',
            'readOnly'   => true
        ],
        CmsObject::FIELD_OWNER => [
            'type' => IField::TYPE_INTEGER,
            'columnName' => 'owner_id',
        ],
        CmsObject::FIELD_EDITOR => [
            'type' => IField::TYPE_INTEGER,
            'columnName' => 'editor_id',
        ],
        'active'                                 => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        'locked'                                 => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'defaultValue' => 0
        ],
        'created'                                => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created'],
        'updated'                                => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated'],
        'displayName'                            => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        'post'                                   => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'post_id',
            'target'     => 'blogPost'
        ],
        'contents'                                => ['type' => IField::TYPE_TEXT, 'columnName' => 'contents'],
        'date'                                   => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'date'],
        'answers'                                => [
            'type'        => IField::TYPE_HAS_MANY,
            'target'      => 'blogComment',
            'targetField' => 'parent'
        ]

    ],
    'types'      => [
        'base' => [
            'fields' => [
                'id',
                'guid',
                'type',
                'version',
                'parent',
                'mpath',
                'slug',
                'uri',
                'level',
                'order',
                'childCount',
                CmsObject::FIELD_OWNER,
                CmsObject::FIELD_EDITOR,
                'active',
                'locked',
                'created',
                'updated',
                'displayName',
                'post',
                'contents',
                'date',
                'answers'
            ]
        ]
    ]
];
