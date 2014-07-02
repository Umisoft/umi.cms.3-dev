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
use umicms\project\module\vote\model\object\Vote;

return [
    'dataSource' => [
        'sourceName' => 'umi_vote_vote'
    ],
    'fields'     => [
	    Vote::FIELD_IDENTIFY     => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
	    Vote::FIELD_GUID         => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
	    Vote::FIELD_TYPE         => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
	    Vote::FIELD_VERSION      => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
	    Vote::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
	    Vote::FIELD_CREATED      => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
	    Vote::FIELD_UPDATED      => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
	    Vote::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
	    Vote::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        Vote::FIELD_ANSWERS => [
            'type'       => IField::TYPE_HAS_MANY,
            'columnName' => 'answers',
            'target'     => 'answer'
        ],
        Vote::FIELD_MULTIPLE_CHOICE => [
            'type'       => IField::TYPE_BOOL,
            'columnName' => 'multiple_choice'
        ]

    ],
    'types'      => [
        'base' => [
            'fields' => [
	            Vote::FIELD_IDENTIFY,
	            Vote::FIELD_GUID,
	            Vote::FIELD_TYPE,
	            Vote::FIELD_VERSION,
	            Vote::FIELD_CREATED,
	            Vote::FIELD_UPDATED,
	            Vote::FIELD_DISPLAY_NAME,
	            Vote::FIELD_OWNER,
	            Vote::FIELD_EDITOR,
	            Vote::FIELD_ANSWERS,
	            Vote::FIELD_MULTIPLE_CHOICE
            ]
        ]
    ]
];
