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
use umicms\project\module\news\api\object\RssImportItem;

return [
    'dataSource' => [
        'sourceName' => 'umi_rss_rss_item'
    ],
    'fields'     => [

        RssImportItem::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        RssImportItem::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        RssImportItem::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        RssImportItem::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        RssImportItem::FIELD_DISPLAY_NAME          => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        RssImportItem::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        RssImportItem::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        RssImportItem::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        RssImportItem::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        RssImportItem::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        RssImportItem::FIELD_RSS_URL => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'rss_url',
            'accessor'   => 'getRssUrl'
        ],
        RssImportItem::FIELD_RUBRIC => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target' => 'newsRubric'
        ],
        RssImportItem::FIELD_SUBJECTS => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'newsSubject',
            'bridge'       => 'rssItemSubject',
            'relatedField' => 'rssImportItem',
            'targetField'  => 'subject'
        ],
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\api\object\RssImportItem',
            'fields'      => [
                RssImportItem::FIELD_IDENTIFY,
                RssImportItem::FIELD_GUID,
                RssImportItem::FIELD_TYPE,
                RssImportItem::FIELD_VERSION,
                RssImportItem::FIELD_LOCKED,
                RssImportItem::FIELD_CREATED,
                RssImportItem::FIELD_UPDATED,
                RssImportItem::FIELD_DISPLAY_NAME,
                RssImportItem::FIELD_OWNER,
                RssImportItem::FIELD_EDITOR,
                RssImportItem::FIELD_RSS_URL,
                RssImportItem::FIELD_RUBRIC,
                RssImportItem::FIELD_SUBJECTS
            ]
        ]
    ]
];
