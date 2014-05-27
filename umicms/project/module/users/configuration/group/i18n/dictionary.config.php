<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\users\api\object\UserGroup;

return [
        'en-US' => [
            UserGroup::FIELD_USERS => 'Users',
            UserGroup::FIELD_ROLES => 'Roles',

            'type:base:displayName' => 'User group',
        ],

        'ru-RU' => [
            UserGroup::FIELD_USERS => 'Пользователи',
            UserGroup::FIELD_ROLES => 'Роли',

            'type:base:displayName' => 'Группа пользователей',
        ]
    ];