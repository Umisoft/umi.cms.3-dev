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
use umicms\project\module\news\api\object\RssItem;

return [
    'dataSource' => [
        'sourceName' => 'umi_rss_rss_item'
    ],
    'fields'     => [

        RssItem::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        RssItem::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        RssItem::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        RssItem::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        RssItem::FIELD_DISPLAY_NAME          => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        RssItem::FIELD_ACTIVE                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        RssItem::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        RssItem::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        RssItem::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        RssItem::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        RssItem::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        RssItem::FIELD_RSS_URL => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'rss_url',
            'accessor'   => 'getRssUrl'
        ],
        RssItem::FIELD_CHARSET_RSS => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'charset_rss',
            'accessor'   => 'getCharsetRss'
        ],
        RssItem::FIELD_RUBRIC => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target' => 'newsRubric'
        ],
        RssItem::FIELD_SUBJECTS => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'newsSubject',
            'bridge'       => 'rssItemSubject',
            'relatedField' => 'rssItem',
            'targetField'  => 'subject'
        ],
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\api\object\RssItem',
            'fields'      => [
                RssItem::FIELD_IDENTIFY,
                RssItem::FIELD_GUID,
                RssItem::FIELD_TYPE,
                RssItem::FIELD_VERSION,
                RssItem::FIELD_ACTIVE,
                RssItem::FIELD_LOCKED,
                RssItem::FIELD_CREATED,
                RssItem::FIELD_UPDATED,
                RssItem::FIELD_DISPLAY_NAME,
                RssItem::FIELD_OWNER,
                RssItem::FIELD_EDITOR,
                RssItem::FIELD_RSS_URL,
                RssItem::FIELD_CHARSET_RSS,
                RssItem::FIELD_RUBRIC,
                RssItem::FIELD_SUBJECTS
            ]
        ]
    ]
];
