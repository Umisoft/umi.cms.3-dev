<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\service\object\Backup;

return [
    'dataSource' => [
        'sourceName' => 'umi_backup'
    ],
    'fields' => [
        Backup::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId'
        ],
        Backup::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'mutator' => 'setGuid'
        ],
        Backup::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        Backup::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'mutator' => 'setVersion',
            'defaultValue' => 1
        ],
        Backup::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        Backup::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        Backup::FIELD_OBJECT_ID => [
            'type' => IField::TYPE_INTEGER,
            'columnName' => 'object_id'
        ],
        Backup::FIELD_COLLECTION_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'collection_name'
        ],
        Backup::FIELD_DATE => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'date'
        ],
        Backup::FIELD_USER => [
            'type' => IField::TYPE_INTEGER,
            'columnName' => 'user',
        ],
        Backup::FIELD_DATA => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'data',
            'accessor' => 'getData',
            'readOnly' => true
        ]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\service\object\Backup',
            'fields' => [
                Backup::FIELD_IDENTIFY,
                Backup::FIELD_GUID,
                Backup::FIELD_TYPE,
                Backup::FIELD_VERSION,
                Backup::FIELD_OBJECT_ID,
                Backup::FIELD_OWNER,
                Backup::FIELD_EDITOR,
                Backup::FIELD_COLLECTION_NAME,
                Backup::FIELD_DATE,
                Backup::FIELD_USER,
                Backup::FIELD_DATA
            ]
        ]
    ]
];