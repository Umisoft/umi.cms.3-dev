<?php

use umicms\project\module\seo\model\MegaindexModel;

return [
    'en-US' => [
        'component:megaindex:displayName' => 'MegaIndex',
        MegaindexModel::MEGAINDEX_LOGIN => 'Login',
        MegaindexModel::MEGAINDEX_PASSWORD => 'Password',
        MegaindexModel::MEGAINDEX_SITE_URL => 'Site url',

        'role:configurator:displayName' => 'Managing setting'
    ],

    'ru-RU' => [
        'component:megaindex:displayName' => 'MegaIndex',
        MegaindexModel::MEGAINDEX_LOGIN => 'Логин',
        MegaindexModel::MEGAINDEX_PASSWORD => 'Пароль',
        MegaindexModel::MEGAINDEX_SITE_URL => 'Адрес анализируемого сайта',

        'role:configurator:displayName' => 'Управление настройками'
    ]
];
 