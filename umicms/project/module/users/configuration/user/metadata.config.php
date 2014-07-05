<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\filter\HtmlPurifier;
use umicms\project\Environment;
use umicms\project\module\users\model\object\BaseUser;
use umicms\project\module\users\model\object\Guest;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\object\Supervisor;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/collection.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/metadata/active.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/metadata/locked.config.php',
    [
        'dataSource' => [
            'sourceName' => 'users_user'
        ],
        'fields'     => [
            BaseUser::FIELD_GROUPS                  => [
                'type'         => IField::TYPE_MANY_TO_MANY,
                'target'       => 'userGroup',
                'bridge'       => 'userUserGroup',
                'relatedField' => 'user',
                'targetField'  => 'userGroup'
            ],
            RegisteredUser::FIELD_LOGIN             => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'login',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    HtmlPurifier::TYPE               => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
            RegisteredUser::FIELD_EMAIL             => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'email',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    HtmlPurifier::TYPE               => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => [],
                    IValidatorFactory::TYPE_EMAIL    => [],
                ]
            ],
            RegisteredUser::FIELD_PASSWORD          => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'password',
                'mutator'    => 'setPassword',
                'accessor'   => 'getPassword',
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
            RegisteredUser::FIELD_PASSWORD_SALT     => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'password_salt',
                'readOnly'   => true
            ],
            RegisteredUser::FIELD_ACTIVATION_CODE   => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'activation_code',
                'readOnly'   => true
            ],
            RegisteredUser::FIELD_FIRST_NAME        => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'first_name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    HtmlPurifier::TYPE               => []
                ]
            ],
            RegisteredUser::FIELD_MIDDLE_NAME       => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'middle_name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    HtmlPurifier::TYPE               => []
                ]
            ],
            RegisteredUser::FIELD_LAST_NAME         => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'last_name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    HtmlPurifier::TYPE               => []
                ]
            ],
            RegisteredUser::FIELD_REGISTRATION_DATE => [
                'type'       => IField::TYPE_DATE_TIME,
                'columnName' => 'registration_date'
            ],

        ],
        'types'      => [
            'base'                    => [
                'objectClass' => 'umicms\project\module\users\model\object\BaseUser',
                'fields'      => []
            ],
            Guest::TYPE_NAME          => [
                'objectClass' => 'umicms\project\module\users\model\object\Guest',
                'fields'      => [
                    Guest::FIELD_GROUPS
                ]
            ],
            RegisteredUser::TYPE_NAME => [
                'objectClass' => 'umicms\project\module\users\model\object\RegisteredUser',
                'fields'      => [
                    RegisteredUser::FIELD_GROUPS,
                    RegisteredUser::FIELD_LOGIN,
                    RegisteredUser::FIELD_EMAIL,
                    RegisteredUser::FIELD_PASSWORD,
                    RegisteredUser::FIELD_PASSWORD_SALT,
                    RegisteredUser::FIELD_ACTIVATION_CODE,
                    RegisteredUser::FIELD_FIRST_NAME,
                    RegisteredUser::FIELD_MIDDLE_NAME,
                    RegisteredUser::FIELD_LAST_NAME,
                    RegisteredUser::FIELD_REGISTRATION_DATE
                ]
            ],
            Supervisor::TYPE_NAME     => [
                'objectClass' => 'umicms\project\module\users\model\object\Supervisor'
            ]
        ]
    ]
);
