<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
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

            'type:base:displayName' => 'User',
            'type:guest:displayName' => 'Guest',
            'type:authorized:displayName' => 'Authorized user',
            'type:authorized.supervisor:displayName' => 'Supervisor',

            'locale' => 'Language'
        ],

        'ru-RU' => [
            AuthorizedUser::FIELD_PASSWORD => 'Пароль',
            AuthorizedUser::FIELD_LOGIN => 'Логин',
            AuthorizedUser::FIELD_EMAIL => 'E-mail',
            AuthorizedUser::FIELD_FIRST_NAME => 'Имя',
            AuthorizedUser::FIELD_MIDDLE_NAME => 'Отчество',
            AuthorizedUser::FIELD_LAST_NAME => 'Фамилия',

            'type:base:displayName' => 'Пользователь',
            'type:guest:displayName' => 'Гость',
            'type:authorized:displayName' => 'Авторизованный пользователь',
            'type:authorized.supervisor:displayName' => 'Супервайзер',

            'locale' => 'Язык'
        ]
    ];