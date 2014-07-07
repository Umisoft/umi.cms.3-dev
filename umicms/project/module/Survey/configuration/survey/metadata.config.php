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
use umicms\project\module\survey\model\object\Survey;

return [
    'dataSource' => [
        'sourceName' => 'umi_survey_survey'
    ],
    'fields' => array(
        Survey::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        Survey::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        Survey::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        Survey::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        Survey::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        Survey::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly' => true
        ],
        Survey::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly' => true
        ],
        Survey::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        Survey::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        Survey::FIELD_ANSWERS => [
            'type' => IField::TYPE_HAS_MANY,
            'columnName' => 'answers',
            'target' => 'answer'
        ],
        Survey::FIELD_MULTIPLE_CHOICE => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'multiple_choice'
        ]

    ),
    'types' => [
        'base' => [
            'fields' => [
                Survey::FIELD_IDENTIFY,
                Survey::FIELD_GUID,
                Survey::FIELD_TYPE,
                Survey::FIELD_VERSION,
                Survey::FIELD_CREATED,
                Survey::FIELD_UPDATED,
                Survey::FIELD_DISPLAY_NAME,
                Survey::FIELD_OWNER,
                Survey::FIELD_EDITOR,
                Survey::FIELD_ANSWERS,
                Survey::FIELD_MULTIPLE_CHOICE
            ]
        ]
    ]
];
