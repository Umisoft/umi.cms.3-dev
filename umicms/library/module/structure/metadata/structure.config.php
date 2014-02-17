<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\structure\metadata;

use umi\orm\metadata\field\IField;
use umicms\module\structure\model\StructureElement;

return [
    'dataSource' => [
        'sourceName' => 'umi_structure'
    ],
    'fields'     => [

        StructureElement::FIELD_IDENTIFY        => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId'
        ],
        StructureElement::FIELD_GUID            => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'mutator'    => 'setGuid'
        ],
        StructureElement::FIELD_TYPE            => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        StructureElement::FIELD_VERSION         => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'mutator'      => 'setVersion',
            'defaultValue' => 1
        ],
        StructureElement::FIELD_PARENT          => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor'   => 'getParent',
            'target'     => 'structure',
            'readOnly'   => true
        ],
        StructureElement::FIELD_MPATH           => [
            'type'       => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor'   => 'getMaterializedPath',
            'readOnly'   => true
        ],
        StructureElement::FIELD_SLUG            => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor'   => 'getSlug',
            'readOnly'   => true
        ],
        StructureElement::FIELD_URI             => [
            'type'       => IField::TYPE_URI,
            'columnName' => 'uri',
            'accessor'   => 'getURI',
            'readOnly'   => true
        ],
        StructureElement::FIELD_CHILD_COUNT     => [
            'type'         => IField::TYPE_COUNTER,
            'columnName'   => 'child_count',
            'accessor'     => 'getChildCount',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        StructureElement::FIELD_ORDER           => [
            'type'       => IField::TYPE_ORDER,
            'columnName' => 'order',
            'accessor'   => 'getOrder',
            'readOnly'   => true
        ],
        StructureElement::FIELD_HIERARCHY_LEVEL => [
            'type'       => IField::TYPE_LEVEL,
            'columnName' => 'level',
            'accessor'   => 'getLevel',
            'readOnly'   => true
        ],
        'displayName'                           => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        'module'                                => ['type'       => IField::TYPE_STRING,
                                                    'columnName' => 'module',
                                                    'readOnly'   => true
        ],
        'active'                                => ['type'         => IField::TYPE_BOOL,
                                                    'columnName'   => 'active',
                                                    'defaultValue' => 1
        ],
        'locked'                                => ['type'         => IField::TYPE_BOOL,
                                                    'columnName'   => 'locked',
                                                    'defaultValue' => 0
        ],
        'created'                               => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created'],
        'updated'                               => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated'],
        'h1'                                    => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        'metaTitle'                             => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        'metaKeywords'                          => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_keywords'],
        'metaDescription'                       => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_description'],
        'content'                               => ['type' => IField::TYPE_TEXT, 'columnName' => 'content']
    ],
    'types'      => [
        'base'   => [
            'objectClass' => 'umicms\module\structure\model\StructureElement',
            'fields'      => [
                'id',
                'guid',
                'type',
                'version',
                'displayName',
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
                'module'
            ]
        ],
        'system' => [
            'objectClass' => 'umicms\module\structure\model\SystemPage',
            'fields'      => [
                'id',
                'guid',
                'type',
                'version',
                'displayName',
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
                'module'
            ]
        ],
        'static' => [
            'objectClass' => 'umicms\module\structure\model\StaticPage',
            'fields'      => [
                'id',
                'guid',
                'type',
                'version',
                'displayName',
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
                'module',
                'metaTitle',
                'metaKeywords',
                'metaDescription',
                'h1',
                'content'
            ]
        ]
    ]
];