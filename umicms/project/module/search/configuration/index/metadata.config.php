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
use umicms\project\module\search\model\object\SearchIndex;

return [
    'dataSource' => [
        'sourceName' => 'umi_search_index'
    ],
    'fields' => [
        SearchIndex::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId'
        ],
        SearchIndex::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'mutator' => 'setGuid'
        ],
        SearchIndex::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        SearchIndex::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'mutator' => 'setVersion',
            'defaultValue' => 1
        ],
        SearchIndex::FIELD_REF_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'ref_guid',
        ],
        SearchIndex::FIELD_CONTENT => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents',
            'localizations' => [
                'ru-RU' => ['columnName' => 'contents'],
                'en-US' => ['columnName' => 'contents_en']
            ]
        ],
        SearchIndex::FIELD_COLLECTION_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'collection_id',
        ],
        SearchIndex::FIELD_DATE_INDEXED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'date_indexed',
        ],
        SearchIndex::FIELD_CREATED             => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        SearchIndex::FIELD_UPDATED             => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        SearchIndex::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        SearchIndex::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\search\model\object\SearchIndex',
            'fields' => [
                SearchIndex::FIELD_IDENTIFY,
                SearchIndex::FIELD_GUID,
                SearchIndex::FIELD_VERSION,
                SearchIndex::FIELD_REF_GUID,
                SearchIndex::FIELD_CONTENT,
                SearchIndex::FIELD_COLLECTION_NAME,
                SearchIndex::FIELD_DATE_INDEXED,
                SearchIndex::FIELD_OWNER,
                SearchIndex::FIELD_EDITOR,
                SearchIndex::FIELD_CREATED,
                SearchIndex::FIELD_UPDATED
            ]
        ]
    ]
];
