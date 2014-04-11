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
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\object\BaseUser;
use umicms\project\module\users\api\object\Guest;
use umicms\project\module\users\api\object\Supervisor;

return [
    'dataSource' => [
        'sourceName' => 'umi_users'
    ],
    'fields'     => [

        BaseUser::FIELD_IDENTIFY            => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        BaseUser::FIELD_GUID                => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
        ],
        BaseUser::FIELD_TYPE                => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        BaseUser::FIELD_VERSION             => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'readOnly'     => true,
            'defaultValue' => 1
        ],
        BaseUser::FIELD_DISPLAY_NAME        => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters'    => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        BaseUser::FIELD_ACTIVE              => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        BaseUser::FIELD_LOCKED              => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
        BaseUser::FIELD_CREATED             => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly'   => true
        ],
        BaseUser::FIELD_UPDATED             => [
            'type'       => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly'   => true
        ],
        BaseUser::FIELD_GROUPS              => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'userGroup',
            'bridge'       => 'userUserGroup',
            'relatedField' => 'user',
            'targetField'  => 'userGroup'
        ],
        BaseUser::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BaseUser::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        AuthorizedUser::FIELD_TRASHED       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'trashed',
            'defaultValue' => 0,
            'readOnly'     => true,
        ],
        AuthorizedUser::FIELD_LOGIN         => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'login',
            'filters'    => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        AuthorizedUser::FIELD_EMAIL         => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'email',
            'filters'    => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => [],
                IValidatorFactory::TYPE_REGEXP   => [
                    'pattern' => '/.+\@.+\..+/'
                ],
                IValidatorFactory::TYPE_EMAIL    => [],
            ]
        ],
        AuthorizedUser::FIELD_PASSWORD      => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'password',
            'readOnly'   => true
        ],
        AuthorizedUser::FIELD_PASSWORD_SALT => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'password_salt',
            'readOnly'   => true
        ],

    ],
    'types'      => [
        'base'       => [
            'objectClass' => 'umicms\project\module\users\api\object\BaseUser',
            'fields'      => [
                BaseUser::FIELD_IDENTIFY,
                BaseUser::FIELD_GUID,
                BaseUser::FIELD_TYPE,
                BaseUser::FIELD_VERSION,
                BaseUser::FIELD_ACTIVE,
                BaseUser::FIELD_LOCKED,
                BaseUser::FIELD_CREATED,
                BaseUser::FIELD_UPDATED,
                BaseUser::FIELD_DISPLAY_NAME,
                BaseUser::FIELD_GROUPS,
                BaseUser::FIELD_OWNER,
                BaseUser::FIELD_EDITOR
            ]
        ],
        'guest'      => [
            'objectClass' => 'umicms\project\module\users\api\object\Guest',
            'fields'      => [
                Guest::FIELD_IDENTIFY,
                Guest::FIELD_GUID,
                Guest::FIELD_TYPE,
                Guest::FIELD_VERSION,
                Guest::FIELD_ACTIVE,
                Guest::FIELD_LOCKED,
                Guest::FIELD_CREATED,
                Guest::FIELD_UPDATED,
                Guest::FIELD_DISPLAY_NAME,
                Guest::FIELD_GROUPS,
                Guest::FIELD_OWNER,
                Guest::FIELD_EDITOR
            ]
        ],
        'authorized' => [
            'objectClass' => 'umicms\project\module\users\api\object\AuthorizedUser',
            'fields'      => [
                AuthorizedUser::FIELD_IDENTIFY,
                AuthorizedUser::FIELD_GUID,
                AuthorizedUser::FIELD_TYPE,
                AuthorizedUser::FIELD_VERSION,
                AuthorizedUser::FIELD_ACTIVE,
                AuthorizedUser::FIELD_LOCKED,
                AuthorizedUser::FIELD_TRASHED,
                AuthorizedUser::FIELD_CREATED,
                AuthorizedUser::FIELD_UPDATED,
                AuthorizedUser::FIELD_DISPLAY_NAME,
                AuthorizedUser::FIELD_LOGIN,
                AuthorizedUser::FIELD_EMAIL,
                AuthorizedUser::FIELD_PASSWORD,
                AuthorizedUser::FIELD_PASSWORD_SALT,
                AuthorizedUser::FIELD_GROUPS,
                AuthorizedUser::FIELD_OWNER,
                AuthorizedUser::FIELD_EDITOR,
            ]
        ],
        'authorized.supervisor' => [
            'objectClass' => 'umicms\project\module\users\api\object\Supervisor',
            'fields'      => [
                Supervisor::FIELD_IDENTIFY,
                Supervisor::FIELD_GUID,
                Supervisor::FIELD_TYPE,
                Supervisor::FIELD_VERSION,
                Supervisor::FIELD_ACTIVE,
                Supervisor::FIELD_LOCKED,
                Supervisor::FIELD_TRASHED,
                Supervisor::FIELD_CREATED,
                Supervisor::FIELD_UPDATED,
                Supervisor::FIELD_DISPLAY_NAME,
                Supervisor::FIELD_LOGIN,
                Supervisor::FIELD_EMAIL,
                Supervisor::FIELD_PASSWORD,
                Supervisor::FIELD_PASSWORD_SALT,
                Supervisor::FIELD_GROUPS,
                Supervisor::FIELD_OWNER,
                Supervisor::FIELD_EDITOR,
            ]
        ]
    ]
];
