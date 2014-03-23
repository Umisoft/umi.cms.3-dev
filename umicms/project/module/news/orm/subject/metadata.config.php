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
use umicms\project\module\news\object\NewsSubject;

return [
    'dataSource' => [
        'sourceName' => 'umi_news_subject'
    ],
    'fields'     => [
        NewsSubject::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        NewsSubject::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        NewsSubject::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        NewsSubject::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        NewsSubject::FIELD_DISPLAY_NAME          => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        NewsSubject::FIELD_ACTIVE                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        NewsSubject::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        NewsSubject::FIELD_TRASHED               => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'trashed',
            'defaultValue' => 0,
            'readOnly'     => true,
        ],
        NewsSubject::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        NewsSubject::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        NewsSubject::FIELD_PAGE_META_TITLE       => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        NewsSubject::FIELD_PAGE_META_KEYWORDS    => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_keywords'
        ],
        NewsSubject::FIELD_PAGE_META_DESCRIPTION => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_description'
        ],
        NewsSubject::FIELD_PAGE_H1               => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        NewsSubject::FIELD_PAGE_CONTENTS         => ['type' => IField::TYPE_TEXT, 'columnName' => 'contents'],
        NewsSubject::FIELD_PAGE_LAYOUT           => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target'     => 'layout'
        ],
        NewsSubject::FIELD_PAGE_SLUG             => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug'
        ],
        NewsSubject::FIELD_NEWS => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'newsItem',
            'bridge'       => 'newsItemSubject',
            'relatedField' => 'subject',
            'targetField'  => 'newsItem',
            'readOnly'     => true
        ],
        NewsSubject::FIELD_OWNER => [
            'type' => IField::TYPE_INTEGER,
            'columnName' => 'owner_id',
        ],
        NewsSubject::FIELD_EDITOR => [
            'type' => IField::TYPE_INTEGER,
            'columnName' => 'editor_id',
        ]
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\object\NewsSubject',
            'fields'      => [
                NewsSubject::FIELD_IDENTIFY,
                NewsSubject::FIELD_GUID,
                NewsSubject::FIELD_TYPE,
                NewsSubject::FIELD_VERSION,
                NewsSubject::FIELD_ACTIVE,
                NewsSubject::FIELD_LOCKED,
                NewsSubject::FIELD_TRASHED,
                NewsSubject::FIELD_CREATED,
                NewsSubject::FIELD_UPDATED,
                NewsSubject::FIELD_TRASHED,
                NewsSubject::FIELD_DISPLAY_NAME,
                NewsSubject::FIELD_PAGE_META_TITLE,
                NewsSubject::FIELD_PAGE_META_KEYWORDS,
                NewsSubject::FIELD_PAGE_META_DESCRIPTION,
                NewsSubject::FIELD_PAGE_H1,
                NewsSubject::FIELD_PAGE_CONTENTS,
                NewsSubject::FIELD_PAGE_LAYOUT,
                NewsSubject::FIELD_PAGE_SLUG,
                NewsSubject::FIELD_NEWS,
                NewsSubject::FIELD_OWNER,
                NewsSubject::FIELD_EDITOR
            ]
        ]
    ]
];
