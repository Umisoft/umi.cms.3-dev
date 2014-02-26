<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\metadata;

use umi\orm\metadata\field\IField;
use umicms\project\module\news\object\NewsItem;

return [
    'dataSource' => [
        'sourceName' => 'umi_news_item'
    ],
    'fields'     => [

        NewsItem::FIELD_IDENTIFY     => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId'
        ],
        NewsItem::FIELD_GUID         => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'mutator'    => 'setGuid'
        ],
        NewsItem::FIELD_TYPE         => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        NewsItem::FIELD_VERSION      => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'mutator'      => 'setVersion',
            'defaultValue' => 1
        ],
        NewsItem::FIELD_DISPLAY_NAME => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        NewsItem::FIELD_ACTIVE       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        NewsItem::FIELD_LOCKED       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'defaultValue' => 0
        ],
        NewsItem::FIELD_CREATED      => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created'],
        NewsItem::FIELD_UPDATED      => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated'],
        'h1'                         => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        'metaTitle'                  => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        'metaKeywords'               => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_keywords'],
        'metaDescription'            => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_description'],
        'content'                    => ['type' => IField::TYPE_TEXT, 'columnName' => 'content'],
        NewsItem::FIELD_ANNOUNCEMENT => ['type' => IField::TYPE_TEXT, 'columnName' => 'announcement'],
        NewsItem::FIELD_RUBRIC       => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target'     => 'NewsRubric'
        ],
        NewsItem::FIELD_SLUG         => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor'   => 'getSlug',
            'mutator'    => 'setSlug'
        ],
        NewsItem::FIELD_SUBJECTS     => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'NewsSubject',
            'bridge'       => 'NewsItemSubject',
            'relatedField' => 'newsItem',
            'targetField'  => 'subject'
        ],
        NewsItem::FIELD_DATE         => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'date']

    ],
    'types'      => [
        'base'        => [
            'objectClass' => 'umicms\project\module\news\object\NewsItem',
            'fields' => [
                NewsItem::FIELD_IDENTIFY,
                NewsItem::FIELD_GUID,
                NewsItem::FIELD_TYPE,
                NewsItem::FIELD_VERSION,
                NewsItem::FIELD_ACTIVE,
                NewsItem::FIELD_LOCKED,
                NewsItem::FIELD_CREATED,
                NewsItem::FIELD_UPDATED,
                NewsItem::FIELD_DISPLAY_NAME,
                NewsItem::FIELD_RUBRIC,
                NewsItem::FIELD_SLUG,
                'h1',
                'metaTitle',
                'metaKeywords',
                'metaDescription',
                'content',
                NewsItem::FIELD_ANNOUNCEMENT,
                NewsItem::FIELD_SUBJECTS,
                NewsItem::FIELD_DATE
            ]
        ]
    ]
];
