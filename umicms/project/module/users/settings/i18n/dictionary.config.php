<?php

use umicms\project\module\users\api\collection\UserCollection;

return [
    'en-US' => [
        'component:users:displayName' => 'Users',
        UserCollection::SETTING_REGISTRATION_WITH_ACTIVATION => 'Registration with activation',
        UserCollection::SETTING_MIN_PASSWORD_LENGTH => 'Minimal password length',
        UserCollection::SETTING_FORBID_PASSWORD_LOGIN_EQUALITY => 'Forbid password and login equality',
        UserCollection::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS => 'Registered users default groups'
    ],

    'ru-RU' => [
        'component:users:displayName' => 'Пользователи',
        UserCollection::SETTING_REGISTRATION_WITH_ACTIVATION => 'Регистрация с активацией',
        UserCollection::SETTING_MIN_PASSWORD_LENGTH => 'Минимальная длина пароля',
        UserCollection::SETTING_FORBID_PASSWORD_LOGIN_EQUALITY => 'Запретить совпадение пароля с логином',
        UserCollection::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS => 'Группы зарегистрированных пользователей по умолчанию'

    ]
];
 