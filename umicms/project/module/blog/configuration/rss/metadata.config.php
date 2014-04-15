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
use umicms\project\module\blog\api\object\RssImportPost;

return [
    'dataSource' => [
        'sourceName' => 'umi_rss_rss_post'
    ],
    'fields'     => [

        RssImportPost::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        RssImportPost::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        RssImportPost::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        RssImportPost::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        RssImportPost::FIELD_DISPLAY_NAME          => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        RssImportPost::FIELD_ACTIVE                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        RssImportPost::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        RssImportPost::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        RssImportPost::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        RssImportPost::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        RssImportPost::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        RssImportPost::FIELD_RSS_URL => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'rss_url',
            'accessor'   => 'getRssUrl'
        ],
        RssImportPost::FIELD_CATEGORY => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target' => 'blogCategory'
        ],
        RssImportPost::FIELD_TAGS => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'blogTag',
            'bridge'       => 'rssBlogTag',
            'relatedField' => 'rssImportPost',
            'targetField'  => 'tag'
        ],
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\blog\api\object\RssImportPost',
            'fields'      => [
                RssImportPost::FIELD_IDENTIFY,
                RssImportPost::FIELD_GUID,
                RssImportPost::FIELD_TYPE,
                RssImportPost::FIELD_VERSION,
                RssImportPost::FIELD_ACTIVE,
                RssImportPost::FIELD_LOCKED,
                RssImportPost::FIELD_CREATED,
                RssImportPost::FIELD_UPDATED,
                RssImportPost::FIELD_DISPLAY_NAME,
                RssImportPost::FIELD_OWNER,
                RssImportPost::FIELD_EDITOR,
                RssImportPost::FIELD_RSS_URL,
                RssImportPost::FIELD_CATEGORY,
                RssImportPost::FIELD_TAGS
            ]
        ]
    ]
];
