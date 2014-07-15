<?php


return [
    'locales'       => [
        'site'  => [
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
    'routes'        => [
        'en-US'   => [
            'type'     => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/twig/en',
                'locale' => 'en-US'
            ]
        ],
        'default' => [
            'type'     => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/twig',
                'locale' => 'ru-RU'
            ]
        ]
    ],
    'defaultLocale' => 'ru-RU',
    'dumpDestination' => '~/common/dump/demo'
];