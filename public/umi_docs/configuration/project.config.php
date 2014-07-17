<?php

return  [
    'locales' => [
        'site' => [
            'ru-RU' => [
                'route' => 'default'
            ]
        ],
        'admin' => [
            'ru-RU' => [],
            'en-US' => []
        ]
    ],

    'routes' => [
        'default' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/docs',
                'locale' => 'ru-RU'
            ]
        ]
    ],

    'defaultLocale' => 'ru-RU'
];