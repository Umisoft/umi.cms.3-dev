<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\module\news\model\object\NewsItem;

return [
    'dataSource' => [
        'sourceName' => 'project_news_item'
    ],
    'fields'     => [

        NewsItem::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        NewsItem::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        NewsItem::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        NewsItem::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        NewsItem::FIELD_DISPLAY_NAME          => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        NewsItem::FIELD_ACTIVE                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        NewsItem::FIELD_TRASHED               => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'trashed',
            'defaultValue' => 0,
            'readOnly'     => true,
        ],
        NewsItem::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        NewsItem::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        NewsItem::FIELD_PAGE_META_TITLE       => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        NewsItem::FIELD_PAGE_META_KEYWORDS    => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_keywords'
        ],
        NewsItem::FIELD_PAGE_META_DESCRIPTION => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_description'
        ],
        NewsItem::FIELD_PAGE_H1               => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        NewsItem::FIELD_PAGE_CONTENTS         => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents',
            'localizations' => [
                'ru-RU' => ['columnName' => 'contents'],
                'en-US' => ['columnName' => 'contents_en']
            ]
        ],
        NewsItem::FIELD_PAGE_LAYOUT           => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target'     => 'layout'
        ],
        NewsItem::FIELD_PAGE_SLUG             => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug'
        ],
        NewsItem::FIELD_ANNOUNCEMENT          => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'announcement',
            'localizations' => [
                'ru-RU' => ['columnName' => 'announcement'],
                'en-US' => ['columnName' => 'announcement_en']
            ]
        ],
        NewsItem::FIELD_SOURCE          => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'source'
        ],
        NewsItem::FIELD_RUBRIC                => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target'     => 'newsRubric'
        ],
        NewsItem::FIELD_SUBJECTS              => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'newsSubject',
            'bridge'       => 'newsItemSubject',
            'relatedField' => 'newsItem',
            'targetField'  => 'subject'
        ],
        NewsItem::FIELD_DATE                  => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'date'
        ],
        NewsItem::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        NewsItem::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ]
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\model\object\NewsItem',
            'fields'      => [
                NewsItem::FIELD_IDENTIFY,
                NewsItem::FIELD_GUID,
                NewsItem::FIELD_TYPE,
                NewsItem::FIELD_VERSION,
                NewsItem::FIELD_ACTIVE,
                NewsItem::FIELD_TRASHED,
                NewsItem::FIELD_CREATED,
                NewsItem::FIELD_UPDATED,
                NewsItem::FIELD_DISPLAY_NAME,
                NewsItem::FIELD_PAGE_META_TITLE,
                NewsItem::FIELD_PAGE_META_KEYWORDS,
                NewsItem::FIELD_PAGE_META_DESCRIPTION,
                NewsItem::FIELD_PAGE_H1,
                NewsItem::FIELD_PAGE_CONTENTS,
                NewsItem::FIELD_PAGE_LAYOUT,
                NewsItem::FIELD_PAGE_SLUG,
                NewsItem::FIELD_RUBRIC,
                NewsItem::FIELD_ANNOUNCEMENT,
                NewsItem::FIELD_SOURCE,
                NewsItem::FIELD_SUBJECTS,
                NewsItem::FIELD_DATE,
                NewsItem::FIELD_OWNER,
                NewsItem::FIELD_EDITOR
            ]
        ]
    ]
];
