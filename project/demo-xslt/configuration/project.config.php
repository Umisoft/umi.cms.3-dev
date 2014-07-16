<?php

return [
    'locales' => [
        'site' => [
            'ru-RU' => [
                'route' => 'default'
            ],
            'en-US' => [
                'route' => 'en-US'
            ]
        ],
        'admin' => [
            'ru-RU' => [],
            'en-US' => []
        ]
    ],

    'routes' => [
        'en-US' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/xslt/en',
                'locale' => 'en-US'
            ]
        ],

        'default' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/xslt',
                'locale' => 'ru-RU'
            ]
        ]
    ],
    'defaultLocale' => 'ru-RU'
];