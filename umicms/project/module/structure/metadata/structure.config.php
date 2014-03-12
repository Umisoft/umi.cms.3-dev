<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\metadata;

use umi\orm\metadata\field\IField;
use umicms\project\module\structure\object\StaticPage;
use umicms\project\module\structure\object\StructureElement;
use umicms\project\module\structure\object\SystemPage;

return [
    'dataSource' => [
        'sourceName' => 'umi_structure'
    ],
    'fields'     => [

        StructureElement::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        StructureElement::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        StructureElement::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        StructureElement::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'   => true,
            'defaultValue' => 1
        ],
        StructureElement::FIELD_PARENT                => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor'   => 'getParent',
            'target'     => 'structure',
            'readOnly'   => true
        ],
        StructureElement::FIELD_MPATH                 => [
            'type'       => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor'   => 'getMaterializedPath',
            'readOnly'   => true
        ],
        StructureElement::FIELD_SLUG                  => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor'   => 'getSlug',
            'readOnly'   => true
        ],
        StructureElement::FIELD_URI                   => [
            'type'       => IField::TYPE_URI,
            'columnName' => 'uri',
            'accessor'   => 'getURI',
            'readOnly'   => true
        ],
        StructureElement::FIELD_CHILD_COUNT           => [
            'type'         => IField::TYPE_COUNTER,
            'columnName'   => 'child_count',
            'accessor'     => 'getChildCount',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        StructureElement::FIELD_ORDER                 => [
            'type'       => IField::TYPE_ORDER,
            'columnName' => 'order',
            'accessor'   => 'getOrder',
            'readOnly'   => true
        ],
        StructureElement::FIELD_HIERARCHY_LEVEL       => [
            'type'       => IField::TYPE_LEVEL,
            'columnName' => 'level',
            'accessor'   => 'getLevel',
            'readOnly'   => true
        ],
        StructureElement::FIELD_DISPLAY_NAME          => ['type'       => IField::TYPE_STRING,
                                                          'columnName' => 'display_name'
        ],
        StructureElement::FIELD_COMPONENT_PATH        => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'component_path',
            'readOnly'   => true
        ],
        StructureElement::FIELD_ACTIVE                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        StructureElement::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'   => true,
            'defaultValue' => 0
        ],
        StructureElement::FIELD_TRASHED          => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'trashed',
            'defaultValue' => 0,
            'readOnly'   => true,
        ],
        StructureElement::FIELD_CREATED         => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created'],
        StructureElement::FIELD_UPDATED         => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated'],
        StructureElement::FIELD_PAGE_META_TITLE       => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        StructureElement::FIELD_PAGE_META_KEYWORDS    => ['type'       => IField::TYPE_STRING,
                                                          'columnName' => 'meta_keywords'
        ],
        StructureElement::FIELD_PAGE_META_DESCRIPTION => ['type'       => IField::TYPE_STRING,
                                                          'columnName' => 'meta_description'
        ],
        StructureElement::FIELD_PAGE_H1               => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        StructureElement::FIELD_PAGE_CONTENTS          => ['type' => IField::TYPE_TEXT, 'columnName' => 'contents'],
        StructureElement::FIELD_PAGE_LAYOUT           => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target'     => 'layout'
        ],
        StructureElement::FIELD_CHILDREN              => [
            'type'        => IField::TYPE_HAS_MANY,
            'target'      => 'structure',
            'targetField' => StructureElement::FIELD_PARENT,
            'readOnly'    => true
        ]
    ],
    'types'      => [
        'base'   => [
            'objectClass' => 'umicms\project\module\structure\object\StructureElement',
            'fields'      => [
                StructureElement::FIELD_IDENTIFY,
                StructureElement::FIELD_GUID,
                StructureElement::FIELD_TYPE,
                StructureElement::FIELD_VERSION,
                StructureElement::FIELD_DISPLAY_NAME,
                StructureElement::FIELD_PARENT,
                StructureElement::FIELD_MPATH,
                StructureElement::FIELD_SLUG,
                StructureElement::FIELD_URI,
                StructureElement::FIELD_HIERARCHY_LEVEL,
                StructureElement::FIELD_ORDER,
                StructureElement::FIELD_CHILD_COUNT,
                StructureElement::FIELD_ACTIVE,
                StructureElement::FIELD_LOCKED,
                StructureElement::FIELD_CREATED,
                StructureElement::FIELD_UPDATED,
                StructureElement::FIELD_COMPONENT_PATH,
                StructureElement::FIELD_PAGE_META_TITLE,
                StructureElement::FIELD_PAGE_META_KEYWORDS,
                StructureElement::FIELD_PAGE_META_DESCRIPTION,
                StructureElement::FIELD_PAGE_H1,
                StructureElement::FIELD_PAGE_CONTENTS,
                StructureElement::FIELD_PAGE_LAYOUT,
                StructureElement::FIELD_CHILDREN
            ]
        ],
        'system' => [
            'objectClass' => 'umicms\project\module\structure\object\SystemPage',
            'fields'      => [
                SystemPage::FIELD_IDENTIFY,
                SystemPage::FIELD_GUID,
                SystemPage::FIELD_TYPE,
                SystemPage::FIELD_VERSION,
                SystemPage::FIELD_DISPLAY_NAME,
                SystemPage::FIELD_PARENT,
                SystemPage::FIELD_MPATH,
                SystemPage::FIELD_SLUG,
                SystemPage::FIELD_URI,
                SystemPage::FIELD_HIERARCHY_LEVEL,
                SystemPage::FIELD_ORDER,
                SystemPage::FIELD_CHILD_COUNT,
                SystemPage::FIELD_ACTIVE,
                SystemPage::FIELD_LOCKED,
                SystemPage::FIELD_CREATED,
                SystemPage::FIELD_UPDATED,
                SystemPage::FIELD_COMPONENT_PATH,
                SystemPage::FIELD_PAGE_META_TITLE,
                SystemPage::FIELD_PAGE_META_KEYWORDS,
                SystemPage::FIELD_PAGE_META_DESCRIPTION,
                SystemPage::FIELD_PAGE_H1,
                SystemPage::FIELD_PAGE_CONTENTS,
                SystemPage::FIELD_PAGE_LAYOUT,
                SystemPage::FIELD_CHILDREN
            ]
        ],
        'static' => [
            'objectClass' => 'umicms\project\module\structure\object\StaticPage',
            'fields'      => [
                StaticPage::FIELD_IDENTIFY,
                StaticPage::FIELD_GUID,
                StaticPage::FIELD_TYPE,
                StaticPage::FIELD_VERSION,
                StaticPage::FIELD_DISPLAY_NAME,
                StaticPage::FIELD_PARENT,
                StaticPage::FIELD_MPATH,
                StaticPage::FIELD_SLUG,
                StaticPage::FIELD_URI,
                StaticPage::FIELD_HIERARCHY_LEVEL,
                StaticPage::FIELD_ORDER,
                StaticPage::FIELD_CHILD_COUNT,
                StaticPage::FIELD_ACTIVE,
                StaticPage::FIELD_LOCKED,
                StaticPage::FIELD_CREATED,
                StaticPage::FIELD_UPDATED,
                StaticPage::FIELD_COMPONENT_PATH,
                StaticPage::FIELD_PAGE_META_TITLE,
                StaticPage::FIELD_PAGE_META_KEYWORDS,
                StaticPage::FIELD_PAGE_META_DESCRIPTION,
                StaticPage::FIELD_PAGE_H1,
                StaticPage::FIELD_PAGE_CONTENTS,
                StaticPage::FIELD_PAGE_LAYOUT,
                StaticPage::FIELD_CHILDREN
            ]
        ]
    ]
];
