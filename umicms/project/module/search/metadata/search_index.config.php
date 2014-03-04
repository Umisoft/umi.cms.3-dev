<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\search\metadata;

use umi\orm\metadata\field\IField;
use umicms\project\module\search\object\SearchIndex;

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
            'columnName' => 'content',
        ],
        SearchIndex::FIELD_COLLECTION_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'collection_id',
        ],
        SearchIndex::FIELD_DATE_INDEXED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'date_indexed',
        ],
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\search\object\SearchIndex',
            'fields' => [
                SearchIndex::FIELD_IDENTIFY,
                SearchIndex::FIELD_GUID,
                SearchIndex::FIELD_VERSION,
                SearchIndex::FIELD_REF_GUID,
                SearchIndex::FIELD_CONTENT,
                SearchIndex::FIELD_COLLECTION_NAME,
                SearchIndex::FIELD_DATE_INDEXED,
            ]
        ]
    ]
];
