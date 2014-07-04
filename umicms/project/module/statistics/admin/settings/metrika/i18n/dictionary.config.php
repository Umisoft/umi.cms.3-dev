<?php

use umicms\project\module\statistics\admin\metrika\model\MetrikaModel;

return [
    'en-US' => [
        'component:metrika:displayName' => 'Yandex.Metrika',
        MetrikaModel::OAUTH_TOKEN => 'oAuth token',

        'role:configurator:displayName' => 'Managing setting'
    ],

    'ru-RU' => [
        'component:metrika:displayName' => 'Яндекс.Метрика',
        MetrikaModel::OAUTH_TOKEN => 'Авторизационный токен',

        'role:configurator:displayName' => 'Управление настройками'
    ]
];
 