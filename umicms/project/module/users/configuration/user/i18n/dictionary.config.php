<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\users\api\object\AuthorizedUser;

return [
        'en-US' => [
            AuthorizedUser::FIELD_PASSWORD => 'Password',
            AuthorizedUser::FIELD_LOGIN => 'Login',
            AuthorizedUser::FIELD_EMAIL => 'E-mail',
            AuthorizedUser::FIELD_FIRST_NAME => 'First name',
            AuthorizedUser::FIELD_MIDDLE_NAME => 'Middle name',
            AuthorizedUser::FIELD_LAST_NAME => 'Last name',
            AuthorizedUser::FIELD_GROUPS => 'Groups',
            AuthorizedUser::FIELD_REGISTRATION_DATE => 'Registration date',

            'personal' => 'Personal',

            'type:base:displayName' => 'User',
            'type:guest:displayName' => 'Guest',
            'type:authorized:displayName' => 'Registered user',
            'type:authorized.supervisor:displayName' => 'Supervisor',
            'type:authorized.supervisor:createLabel' => 'supervisor',

            'locale' => 'Language'
        ],

        'ru-RU' => [
            AuthorizedUser::FIELD_PASSWORD => 'Пароль',
            AuthorizedUser::FIELD_LOGIN => 'Логин',
            AuthorizedUser::FIELD_EMAIL => 'E-mail',
            AuthorizedUser::FIELD_FIRST_NAME => 'Имя',
            AuthorizedUser::FIELD_MIDDLE_NAME => 'Отчество',
            AuthorizedUser::FIELD_LAST_NAME => 'Фамилия',
            AuthorizedUser::FIELD_GROUPS => 'Группы',
            AuthorizedUser::FIELD_REGISTRATION_DATE => 'Дата регистрации',

            'personal' => 'Личная информация',

            'type:base:displayName' => 'Пользователь',
            'type:guest:displayName' => 'Гость',
            'type:authorized:displayName' => 'Зарегистрированный пользователь',
            'type:authorized:createLabel' => 'Добавить пользователя',
            'type:authorized.supervisor:displayName' => 'Супервайзер',
            'type:authorized.supervisor:createLabel' => 'Добавить супервайзера',

            'locale' => 'Язык'
        ]
    ];