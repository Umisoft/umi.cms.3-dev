<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\users\object\Group;

return [
    'dataSource' => [
        'sourceName' => 'umi_groups'
    ],
    'fields'     => [

        Group::FIELD_IDENTIFY      => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        Group::FIELD_GUID          => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        Group::FIELD_TYPE          => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        Group::FIELD_VERSION       => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'   => true,
            'defaultValue' => 1
        ],
        Group::FIELD_DISPLAY_NAME  => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        Group::FIELD_ACTIVE        => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        Group::FIELD_LOCKED        => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'   => true,
            'defaultValue' => 0
        ],
        Group::FIELD_CREATED       => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created', 'readOnly'   => true],
        Group::FIELD_UPDATED       => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated', 'readOnly'   => true],
        Group::FIELD_USERS     => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'user',
            'bridge'       => 'userUserGroup',
            'relatedField' => 'userGroup',
            'targetField'  => 'user',
            'readOnly'     => true
        ],


    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\users\object\Group',
            'fields'      => [
                Group::FIELD_IDENTIFY,
                Group::FIELD_GUID,
                Group::FIELD_TYPE,
                Group::FIELD_VERSION,
                Group::FIELD_ACTIVE,
                Group::FIELD_LOCKED,
                Group::FIELD_CREATED,
                Group::FIELD_UPDATED,
                Group::FIELD_DISPLAY_NAME,
            ]
        ]
    ]
];
