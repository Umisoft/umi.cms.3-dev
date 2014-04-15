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
use umicms\project\module\news\api\object\RssImportScenario;

return [
    'dataSource' => [
        'sourceName' => 'umi_news_rss_import_scenario'
    ],
    'fields'     => [

        RssImportScenario::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        RssImportScenario::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        RssImportScenario::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        RssImportScenario::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        RssImportScenario::FIELD_DISPLAY_NAME          => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        RssImportScenario::FIELD_LOCKED                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        RssImportScenario::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        RssImportScenario::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        RssImportScenario::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        RssImportScenario::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        RssImportScenario::FIELD_RSS_URL => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'rss_url',
            'accessor'   => 'getRssUrl'
        ],
        RssImportScenario::FIELD_RUBRIC => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target' => 'newsRubric'
        ],
        RssImportScenario::FIELD_SUBJECTS => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'newsSubject',
            'bridge'       => 'rssScenarioSubject',
            'relatedField' => 'rssImportScenario',
            'targetField'  => 'subject'
        ],
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\api\object\RssImportScenario',
            'fields'      => [
                RssImportScenario::FIELD_IDENTIFY,
                RssImportScenario::FIELD_GUID,
                RssImportScenario::FIELD_TYPE,
                RssImportScenario::FIELD_VERSION,
                RssImportScenario::FIELD_LOCKED,
                RssImportScenario::FIELD_CREATED,
                RssImportScenario::FIELD_UPDATED,
                RssImportScenario::FIELD_DISPLAY_NAME,
                RssImportScenario::FIELD_OWNER,
                RssImportScenario::FIELD_EDITOR,
                RssImportScenario::FIELD_RSS_URL,
                RssImportScenario::FIELD_RUBRIC,
                RssImportScenario::FIELD_SUBJECTS
            ]
        ]
    ]
];
