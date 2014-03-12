<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\metadata;

use umi\orm\metadata\field\IField;
use umicms\orm\object\CmsObject;

return [
    'dataSource' => [
        'sourceName' => 'umi_news_news_item_subject'
    ],
    'fields'     => [
        CmsObject::FIELD_IDENTIFY     => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        CmsObject::FIELD_GUID         => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        CmsObject::FIELD_TYPE         => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        CmsObject::FIELD_VERSION      => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        CmsObject::FIELD_DISPLAY_NAME => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        CmsObject::FIELD_ACTIVE       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        CmsObject::FIELD_LOCKED       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        CmsObject::FIELD_TRASHED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'trashed',
            'defaultValue' => 0,
            'readOnly'   => true,
        ],
        CmsObject::FIELD_CREATED      => ['type'       => IField::TYPE_DATE_TIME,
                                          'columnName' => 'created',
                                          'readOnly'   => true
        ],
        CmsObject::FIELD_UPDATED      => ['type'       => IField::TYPE_DATE_TIME,
                                          'columnName' => 'updated',
                                          'readOnly'   => true
        ],
        'newsItem'                    => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'news_item_id',
            'target'     => 'newsItem'
        ],
        'subject'                     => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'subject_id',
            'target'     => 'newsSubject'
        ]

    ],
    'types' => [
        'base' => [
            'fields' => [
                CmsObject::FIELD_IDENTIFY,
                CmsObject::FIELD_GUID,
                CmsObject::FIELD_TYPE,
                CmsObject::FIELD_VERSION,
                CmsObject::FIELD_ACTIVE,
                CmsObject::FIELD_LOCKED,
                CmsObject::FIELD_CREATED,
                CmsObject::FIELD_UPDATED,
                CmsObject::FIELD_DISPLAY_NAME,
                'newsItem',
                'subject'
            ]
        ]
    ]
];
