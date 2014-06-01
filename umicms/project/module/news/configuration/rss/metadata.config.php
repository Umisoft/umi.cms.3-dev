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
use umicms\project\module\news\api\object\NewsRssImportScenario;

return [
    'dataSource' => [
        'sourceName' => 'umi_news_rss_import_scenario'
    ],
    'fields'     => [

        NewsRssImportScenario::FIELD_IDENTIFY              => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        NewsRssImportScenario::FIELD_GUID                  => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        NewsRssImportScenario::FIELD_TYPE                  => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        NewsRssImportScenario::FIELD_VERSION               => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        NewsRssImportScenario::FIELD_DISPLAY_NAME          => [
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
        NewsRssImportScenario::FIELD_CREATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        NewsRssImportScenario::FIELD_UPDATED               => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        NewsRssImportScenario::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        NewsRssImportScenario::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        NewsRssImportScenario::FIELD_RSS_URL => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'rss_url',
            'accessor'   => 'getRssUrl'
        ],
        NewsRssImportScenario::FIELD_RUBRIC => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'rubric_id',
            'target' => 'newsRubric'
        ],
        NewsRssImportScenario::FIELD_SUBJECTS => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'newsSubject',
            'bridge'       => 'rssScenarioSubject',
            'relatedField' => 'newsRssImportScenario',
            'targetField'  => 'subject'
        ],
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\api\object\newsRssImportScenario',
            'fields'      => [
                NewsRssImportScenario::FIELD_IDENTIFY,
                NewsRssImportScenario::FIELD_GUID,
                NewsRssImportScenario::FIELD_TYPE,
                NewsRssImportScenario::FIELD_VERSION,
                NewsRssImportScenario::FIELD_CREATED,
                NewsRssImportScenario::FIELD_UPDATED,
                NewsRssImportScenario::FIELD_DISPLAY_NAME,
                NewsRssImportScenario::FIELD_OWNER,
                NewsRssImportScenario::FIELD_EDITOR,
                NewsRssImportScenario::FIELD_RSS_URL,
                NewsRssImportScenario::FIELD_RUBRIC,
                NewsRssImportScenario::FIELD_SUBJECTS
            ]
        ]
    ]
];
