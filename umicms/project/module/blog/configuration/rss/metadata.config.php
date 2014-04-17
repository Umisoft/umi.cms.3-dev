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
use umicms\project\module\blog\api\object\BlogRssImportScenario;

return [
    'dataSource' => [
        'sourceName' => 'umi_rss_rss_post'
    ],
    'fields'     => [

        BlogRssImportScenario::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        BlogRssImportScenario::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        BlogRssImportScenario::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        BlogRssImportScenario::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        BlogRssImportScenario::FIELD_DISPLAY_NAME          => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        BlogRssImportScenario::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        BlogRssImportScenario::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        BlogRssImportScenario::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        BlogRssImportScenario::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BlogRssImportScenario::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        BlogRssImportScenario::FIELD_RSS_URL => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'rss_url',
            'accessor'   => 'getRssUrl'
        ],
        BlogRssImportScenario::FIELD_CATEGORY => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target' => 'blogCategory'
        ],
        BlogRssImportScenario::FIELD_TAGS => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'blogTag',
            'bridge'       => 'rssBlogTag',
            'relatedField' => 'blogRssImportScenario',
            'targetField'  => 'tag'
        ],
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\blog\api\object\BlogRssImportScenario',
            'fields'      => [
                BlogRssImportScenario::FIELD_IDENTIFY,
                BlogRssImportScenario::FIELD_GUID,
                BlogRssImportScenario::FIELD_TYPE,
                BlogRssImportScenario::FIELD_VERSION,
                BlogRssImportScenario::FIELD_LOCKED,
                BlogRssImportScenario::FIELD_CREATED,
                BlogRssImportScenario::FIELD_UPDATED,
                BlogRssImportScenario::FIELD_DISPLAY_NAME,
                BlogRssImportScenario::FIELD_OWNER,
                BlogRssImportScenario::FIELD_EDITOR,
                BlogRssImportScenario::FIELD_RSS_URL,
                BlogRssImportScenario::FIELD_CATEGORY,
                BlogRssImportScenario::FIELD_TAGS
            ]
        ]
    ]
];
