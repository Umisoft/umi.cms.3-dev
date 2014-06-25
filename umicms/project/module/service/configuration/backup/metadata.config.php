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
use umicms\project\module\service\model\object\Backup;

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
        Backup::FIELD_CREATED             => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        Backup::FIELD_UPDATED             => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
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
        Backup::FIELD_DATA => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'data',
            'accessor' => 'getData',
            'readOnly' => true
        ]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\service\model\object\Backup',
            'fields' => [
                Backup::FIELD_IDENTIFY,
                Backup::FIELD_GUID,
                Backup::FIELD_TYPE,
                Backup::FIELD_VERSION,
                Backup::FIELD_OBJECT_ID,
                Backup::FIELD_OWNER,
                Backup::FIELD_EDITOR,
                Backup::FIELD_CREATED,
                Backup::FIELD_UPDATED,
                Backup::FIELD_COLLECTION_NAME,
                Backup::FIELD_DATA
            ]
        ]
    ]
];