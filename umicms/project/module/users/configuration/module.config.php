<?php

use umicms\project\module\users\model\UsersModule;

return [
    'name' => 'users',
    'models' => [
        'group' => '~/project/module/users/configuration/group',
        'user' => '~/project/module/users/configuration/user',
        'userGroup' => '~/project/module/users/configuration/usergroup',
        'userAuthCookie' => '~/project/module/users/configuration/authcookie'
    ],
    'options' => [
        UsersModule::SETTING_AUTH_COOKIE_TTL => '5'
    ]
];