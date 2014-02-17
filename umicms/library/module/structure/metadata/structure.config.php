<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\structure\metadata;

use umi\orm\metadata\field\IField;

return [
    'dataSource' => [
        'sourceName' => 'umi_structure'
    ],
    'fields'     => [
        'id'              => ['type' => IField::TYPE_IDENTIFY, 'columnName' => 'id', 'accessor' => 'getId'],
        'guid'            => ['type' => IField::TYPE_GUID, 'columnName' => 'guid', 'accessor' => 'getGuid'],
        'type'            => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        'version'         => [
            'type'         => IField::TYPE_INTEGER,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'mutator'      => 'setVersion',
            'defaultValue' => 1
        ],
        'displayName'     => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        'parent'          => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor'   => 'getParent',
            'mutator'    => 'setParent',
            'target'     => 'system_structure'
        ],
        'mpath'           => [
            'type'       => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor'   => 'getMaterializedPath',
            'readOnly'   => true
        ],
        'slug'            => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor'   => 'getSlug',
            'mutator'    => 'setSlug'
        ],
        'uri'             => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'uri',
            'accessor'   => 'getURI',
            'readOnly'   => true
        ],
        'level'           => [
            'type'       => IField::TYPE_INTEGER,
            'columnName' => 'level',
            'accessor'   => 'getLevel',
            'readOnly'   => true
        ],
        'order'           => [
            'type'       => IField::TYPE_INTEGER,
            'columnName' => 'order',
            'accessor'   => 'getOrder',
            'readOnly'   => true
        ],
        'childCount'      => [
            'type'         => IField::TYPE_INTEGER,
            'columnName'   => 'child_count',
            'accessor'     => 'getChildCount',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        'module'          => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'module',
            'readOnly'   => true
        ],
        'active'          => ['type' => IField::TYPE_BOOL, 'columnName' => 'active', 'defaultValue' => 1],
        'locked'          => ['type' => IField::TYPE_BOOL, 'columnName' => 'locked', 'defaultValue' => 0],
        'created'         => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created'],
        'updated'         => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated'],
        'h1'              => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        'metaTitle'       => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        'metaKeywords'    => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_keywords'],
        'metaDescription' => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_description'],
        'content'         => ['type' => IField::TYPE_TEXT, 'columnName' => 'content']

    ],
    'types'      => [
        'base' => [
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