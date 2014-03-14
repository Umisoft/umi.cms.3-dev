<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\news\object\NewsItem;
use umicms\project\module\news\object\NewsRubric;

return [
    'dataSource' => [
        'sourceName' => 'umi_news_rubric'
    ],
    'fields'     => [

        NewsRubric::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        NewsRubric::FIELD_PARENT                => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor'   => 'getParent',
            'target'     => 'newsRubric',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_MPATH                 => [
            'type'       => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor'   => 'getMaterializedPath',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_SLUG                  => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor'   => 'getSlug',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_URI                   => [
            'type'       => IField::TYPE_URI,
            'columnName' => 'uri',
            'accessor'   => 'getURI',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_CHILD_COUNT           => [
            'type'         => IField::TYPE_COUNTER,
            'columnName'   => 'child_count',
            'accessor'     => 'getChildCount',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        NewsRubric::FIELD_ORDER                 => [
            'type'       => IField::TYPE_ORDER,
            'columnName' => 'order',
            'accessor'   => 'getOrder',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_HIERARCHY_LEVEL       => [
            'type'       => IField::TYPE_LEVEL,
            'columnName' => 'level',
            'accessor'   => 'getLevel',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_DISPLAY_NAME          => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        NewsRubric::FIELD_ACTIVE                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        NewsRubric::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        NewsRubric::FIELD_TRASHED               => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'trashed',
            'defaultValue' => 0,
            'readOnly'     => true,
        ],
        NewsRubric::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        NewsRubric::FIELD_PAGE_META_TITLE       => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        NewsRubric::FIELD_PAGE_META_KEYWORDS    => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_keywords'
        ],
        NewsRubric::FIELD_PAGE_META_DESCRIPTION => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_description'
        ],
        NewsRubric::FIELD_PAGE_H1               => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        NewsRubric::FIELD_PAGE_CONTENTS         => ['type' => IField::TYPE_TEXT, 'columnName' => 'contents'],
        NewsRubric::FIELD_PAGE_LAYOUT           => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target'     => 'layout'
        ],
        NewsRubric::FIELD_NEWS                  => [
            'type'        => IField::TYPE_HAS_MANY,
            'target'      => 'newsItem',
            'targetField' => NewsItem::FIELD_RUBRIC,
            'readOnly'    => true
        ],
        NewsRubric::FIELD_CHILDREN              => [
            'type'        => IField::TYPE_HAS_MANY,
            'target'      => 'newsRubric',
            'targetField' => NewsRubric::FIELD_PARENT,
            'readOnly'    => true
        ]
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\object\NewsRubric',
            'fields'      => [
                NewsRubric::FIELD_IDENTIFY,
                NewsRubric::FIELD_GUID,
                NewsRubric::FIELD_TYPE,
                NewsRubric::FIELD_VERSION,
                NewsRubric::FIELD_DISPLAY_NAME,
                NewsRubric::FIELD_PARENT,
                NewsRubric::FIELD_MPATH,
                NewsRubric::FIELD_SLUG,
                NewsRubric::FIELD_URI,
                NewsRubric::FIELD_HIERARCHY_LEVEL,
                NewsRubric::FIELD_ORDER,
                NewsRubric::FIELD_CHILD_COUNT,
                NewsRubric::FIELD_ACTIVE,
                NewsRubric::FIELD_LOCKED,
                NewsRubric::FIELD_TRASHED,
                NewsRubric::FIELD_CREATED,
                NewsRubric::FIELD_UPDATED,
                NewsRubric::FIELD_PAGE_META_TITLE,
                NewsRubric::FIELD_PAGE_META_KEYWORDS,
                NewsRubric::FIELD_PAGE_META_DESCRIPTION,
                NewsRubric::FIELD_PAGE_H1,
                NewsRubric::FIELD_PAGE_CONTENTS,
                NewsRubric::FIELD_PAGE_LAYOUT,
                NewsRubric::FIELD_NEWS,
                NewsRubric::FIELD_CHILDREN
            ]
        ]
    ]
];
