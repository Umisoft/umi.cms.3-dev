<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\users\api\object\UserGroup;

return [
        'en-US' => [
            UserGroup::FIELD_USERS => 'Users',
            UserGroup::FIELD_ROLES => 'Roles',

            'permissions' => 'Permissions',
            'type:base:displayName' => 'User group',
        ],

        'ru-RU' => [
            UserGroup::FIELD_USERS => 'Пользователи',
            UserGroup::FIELD_ROLES => 'Роли',

            'permissions' => 'Права',

            'type:base:displayName' => 'Группа пользователей',
        ]
    ];