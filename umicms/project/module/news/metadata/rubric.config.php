<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\metadata;

use umi\orm\metadata\field\IField;
use umi\orm\object\IHierarchicObject;

return [
    'dataSource' => [
        'sourceName' => 'umi_news_rubric'
    ],
    'fields'     => [

        IHierarchicObject::FIELD_IDENTIFY        => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        IHierarchicObject::FIELD_GUID            => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
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
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        IHierarchicObject::FIELD_PARENT          => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor'   => 'getParent',
            'target'     => 'newsRubric',
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
        'created'                                => ['type'       => IField::TYPE_DATE_TIME,
                                                     'columnName' => 'created',
                                                     'readOnly'   => true
        ],
        'updated'                                => ['type'       => IField::TYPE_DATE_TIME,
                                                     'columnName' => 'updated',
                                                     'readOnly'   => true
        ],
        'displayName'                            => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        'h1'                                     => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        'metaTitle'                              => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        'metaKeywords'                           => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_keywords'],
        'metaDescription'                        => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_description'],
        'content'                                => ['type' => IField::TYPE_TEXT, 'columnName' => 'content'],
        'news'                                   => [
            'type'        => IField::TYPE_HAS_MANY,
            'target'      => 'newsItem',
            'targetField' => 'rubric',
            'readOnly'    => true
        ],
        'children'                               => [
            'type'        => IField::TYPE_HAS_MANY,
            'target'      => 'newsRubric',
            'targetField' => 'parent',
            'readOnly'    => true
        ],

    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\object\NewsRubric',
            'fields'      => [
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
                'active',
                'locked',
                'created',
                'updated',
                'displayName',
                'h1',
                'metaTitle',
                'metaKeywords',
                'metaDescription',
                'content',
                'news',
                'children'
            ]
        ]
    ]
];
