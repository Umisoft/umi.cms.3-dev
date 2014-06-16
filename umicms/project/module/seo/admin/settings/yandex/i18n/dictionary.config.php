<?php

use umicms\project\module\seo\model\YandexModel;

return [
    'en-US' => [
        'component:yandex:displayName' => 'Yandex.Webmaster',
        YandexModel::YANDEX_HOST_ID => 'Host ID',
        YandexModel::YANDEX_OAUTH_TOKEN => 'oAuth token',
    ],

    'ru-RU' => [
        'component:yandex:displayName' => 'Яндекс.Вебмастер',
        YandexModel::YANDEX_HOST_ID => 'Идентификатор хоста',
        YandexModel::YANDEX_OAUTH_TOKEN => 'Авторизационный токен',
    ]
];
 