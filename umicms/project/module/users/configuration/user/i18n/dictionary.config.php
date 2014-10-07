<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\users\model\object\RegisteredUser;

return [
        'en-US' => [
            'collection:user:displayName' => 'Users',

            RegisteredUser::FIELD_PASSWORD => 'Password',
            RegisteredUser::FIELD_LOGIN => 'Login',
            RegisteredUser::FIELD_EMAIL => 'E-mail',
            RegisteredUser::FIELD_FIRST_NAME => 'First name',
            RegisteredUser::FIELD_MIDDLE_NAME => 'Middle name',
            RegisteredUser::FIELD_LAST_NAME => 'Last name',
            RegisteredUser::FIELD_GROUPS => 'Groups',
            RegisteredUser::FIELD_REGISTRATION_DATE => 'Registration date',

            'personal' => 'Personal',

            'type:base:displayName' => 'User',
            'type:guest:displayName' => 'Guest',
            'type:visitor:displayName' => 'Visitor',

            'type:registered:displayName' => 'Registered user',
            'type:registered.supervisor:displayName' => 'Supervisor',
            'type:registered.supervisor:createLabel' => 'supervisor',

            'locale' => 'Language',

            'Log in' => 'Log in',

            'Visitor' => 'Visitor',
            'New password' => 'New password'
        ],

        'ru-RU' => [
            'collection:user:displayName' => 'Пользователи',

            RegisteredUser::FIELD_PASSWORD => 'Пароль',
            RegisteredUser::FIELD_LOGIN => 'Логин',
            RegisteredUser::FIELD_EMAIL => 'E-mail',
            RegisteredUser::FIELD_FIRST_NAME => 'Имя',
            RegisteredUser::FIELD_MIDDLE_NAME => 'Отчество',
            RegisteredUser::FIELD_LAST_NAME => 'Фамилия',
            RegisteredUser::FIELD_GROUPS => 'Группы',
            RegisteredUser::FIELD_REGISTRATION_DATE => 'Дата регистрации',

            'personal' => 'Личная информация',

            'type:base:displayName' => 'Пользователь',
            'type:guest:displayName' => 'Гость',
            'type:visitor:displayName' => 'Посетитель',
            'type:registered:displayName' => 'Зарегистрированный пользователь',
            'type:registered:createLabel' => 'Добавить пользователя',
            'type:registered.supervisor:displayName' => 'Супервайзер',
            'type:registered.supervisor:createLabel' => 'Добавить супервайзера',

            'locale' => 'Язык',

            'Log in' => 'Войти',

            'Visitor' => 'Посетитель',
            'New password' => 'Новый пароль'
        ]
    ];