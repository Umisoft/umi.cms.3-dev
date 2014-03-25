<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\object\UserGroup;

return [
    'dataSource' => [
        'sourceName' => 'umi_groups'
    ],
    'fields'     => [

        UserGroup::FIELD_IDENTIFY      => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        UserGroup::FIELD_GUID          => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        UserGroup::FIELD_TYPE          => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        UserGroup::FIELD_VERSION       => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'   => true,
            'defaultValue' => 1
        ],
        UserGroup::FIELD_DISPLAY_NAME  => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        UserGroup::FIELD_ACTIVE        => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        UserGroup::FIELD_LOCKED        => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'   => true,
            'defaultValue' => 0
        ],
        UserGroup::FIELD_CREATED       => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created', 'readOnly'   => true],
        UserGroup::FIELD_UPDATED       => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated', 'readOnly'   => true],
        UserGroup::FIELD_USERS     => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'user',
            'bridge'       => 'userUserGroup',
            'relatedField' => 'userGroup',
            'targetField'  => 'user',
            'readOnly'     => true
        ],
        UserGroup::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        UserGroup::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        UserGroup::FIELD_ROLES => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'roles',
            'accessor' => 'getRoles',
            'mutator' => 'setRoles'
        ]
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\users\object\UserGroup',
            'fields'      => [
                UserGroup::FIELD_IDENTIFY,
                UserGroup::FIELD_GUID,
                UserGroup::FIELD_TYPE,
                UserGroup::FIELD_VERSION,
                UserGroup::FIELD_ACTIVE,
                UserGroup::FIELD_LOCKED,
                UserGroup::FIELD_CREATED,
                UserGroup::FIELD_UPDATED,
                UserGroup::FIELD_DISPLAY_NAME,
                UserGroup::FIELD_OWNER,
                UserGroup::FIELD_EDITOR,
                UserGroup::FIELD_USERS,
                UserGroup::FIELD_ROLES
            ]
        ]
    ]
];
