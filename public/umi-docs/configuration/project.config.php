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

    'destination' => '~/docs',
    'config' => '~/project/project.config.php',
    'defaultLocale' => 'ru-RU'
];