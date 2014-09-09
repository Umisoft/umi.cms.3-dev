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
    'routes' => [
        'en-US' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/php/en',
                'locale' => 'en-US'
            ]
        ],

        'default' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/php',
                'locale' => 'ru-RU'
            ]
        ]
    ],
    'defaultLocale' => 'ru-RU',

    'commonTemplateDirectory' => '~/project/template/common',
    'defaultPage' => '002675ac-9e29-4675-abf7-aa0f93ff9a8c',
    'defaultLayout' => 'd6cb8b38-7e2d-4b36-8d15-9fe8947d66c7',
    'defaultTemplatingEngineType' => 'php',
    'defaultTemplateExtension' => 'phtml'
];