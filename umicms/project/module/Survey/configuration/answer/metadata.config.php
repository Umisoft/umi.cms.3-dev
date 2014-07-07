<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\survey\model\object\Answer;

return [
    'dataSource' => [
        'sourceName' => 'umi_survey_answer'
    ],
    'fields' => [
        Answer::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        Answer::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        Answer::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        Answer::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        Answer::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        Answer::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly' => true
        ],
        Answer::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly' => true
        ],
        Answer::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        Answer::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        Answer::FIELD_SURVEY => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'survey_id',
            'target' => 'survey'
        ],
        Answer::FIELD_COUNTER => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'counter'
        ]

    ],
    'types' => [
        'base' => [
            'fields' => [
                Answer::FIELD_IDENTIFY,
                Answer::FIELD_GUID,
                Answer::FIELD_TYPE,
                Answer::FIELD_VERSION,
                Answer::FIELD_CREATED,
                Answer::FIELD_UPDATED,
                Answer::FIELD_DISPLAY_NAME,
                Answer::FIELD_OWNER,
                Answer::FIELD_EDITOR,
                Answer::FIELD_SURVEY,
                Answer::FIELD_COUNTER
            ]
        ]
    ]
];
