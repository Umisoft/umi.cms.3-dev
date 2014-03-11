<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\metadata;

use umi\orm\metadata\field\IField;
use umicms\project\module\users\object\User;

return [
    'dataSource' => [
        'sourceName' => 'umi_users'
    ],
    'fields'     => [

        User::FIELD_IDENTIFY      => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        User::FIELD_GUID          => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        User::FIELD_TYPE          => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        User::FIELD_VERSION       => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'   => true,
            'defaultValue' => 1
        ],
        User::FIELD_DISPLAY_NAME  => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        User::FIELD_ACTIVE        => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        User::FIELD_LOCKED        => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'   => true,
            'defaultValue' => 0
        ],
        User::FIELD_TRASHED        => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'trashed',
            'defaultValue' => 0
        ],
        User::FIELD_CREATED       => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created', 'readOnly'   => true],
        User::FIELD_UPDATED       => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated', 'readOnly'   => true],
        User::FIELD_LOGIN         => ['type' => IField::TYPE_STRING, 'columnName' => 'login'],
        User::FIELD_EMAIL         => ['type' => IField::TYPE_STRING, 'columnName' => 'email'],
        User::FIELD_PASSWORD      => ['type' => IField::TYPE_STRING, 'columnName' => 'password', 'readOnly' => true],
        User::FIELD_PASSWORD_SALT => ['type'       => IField::TYPE_STRING,
                                      'columnName' => 'password_salt',
                                      'readOnly'   => true
        ],
        User::FIELD_GROUPS     => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'userGroup',
            'bridge'       => 'userUserGroup',
            'relatedField' => 'user',
            'targetField'  => 'userGroup',
            'readOnly'     => true
        ],

    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\users\object\User',
            'fields'      => [
                User::FIELD_IDENTIFY,
                User::FIELD_GUID,
                User::FIELD_TYPE,
                User::FIELD_VERSION,
                User::FIELD_ACTIVE,
                User::FIELD_LOCKED,
                User::FIELD_CREATED,
                User::FIELD_UPDATED,
                User::FIELD_DISPLAY_NAME,
                User::FIELD_LOGIN,
                User::FIELD_EMAIL,
                User::FIELD_PASSWORD,
                User::FIELD_PASSWORD_SALT,
                User::FIELD_GROUPS
            ]
        ]
    ]
];
