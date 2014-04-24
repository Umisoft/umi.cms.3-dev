<?php

use umi\route\IRouteFactory;

return [
    'xslt' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://realloc.srv09.megaserver.umisoft.ru{uri}',
        'rules' => [
            'uri' => '/xslt'
        ],
        'defaults' => [
            'destination' => '~/lite-xslt',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ],

    'twig' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://realloc.srv09.megaserver.umisoft.ru{uri}',
        'rules' => [
            'uri' => '/twig'
        ],
        'defaults' => [
            'destination' => '~/lite-twig',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ],

    'php_en' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://realloc.srv09.megaserver.umisoft.ru{uri}',
        'rules' => [
            'uri' => '/en'
        ],
        'defaults' => [
            'destination' => '~/lite-php',
            'config' => '~/project/project.config.php',
            'locale' => 'en-US'
        ]
    ],

    'php_ru' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://realloc.srv09.megaserver.umisoft.ru',
        'defaults' => [
            'destination' => '~/lite-php',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ],
    'dasha_ru' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://xn--80aak5f.xn--p1ai',
        'defaults' => [
            'destination' => '~/lite-php',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ]
];