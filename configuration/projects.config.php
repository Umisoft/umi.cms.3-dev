<?php
use umi\route\IRouteFactory;

return [
    'demohunt_en' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://localhost:8081/{uri}',
        'rules' => [
            'uri' => '/en'
        ],
        'defaults' => [
            'destination' => '~/demohunt',
            'config' => '~/project/project.config.php',
            'locale' => 'en-US'
        ]
    ],
    'demohunt_ru' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://localhost:8081',
        'defaults' => [
            'destination' => '~/demohunt',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ],
    'dasha_ru' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://xn--80aak5f.xn--p1ai',
        'defaults' => [
            'destination' => '~/demohunt',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ]
];
