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

    'demohunt_en' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://umicms3{uri}',
        'rules' => [
            'uri' => '/en'
        ],
        'defaults' => [
            'destination' => '~/demohunt',
            'config' => '~/project/project.config.php',
            'locale' => 'en-US'
        ]
    ],
    'demohunt_twig' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://umicms3{uri}',
        'rules' => [
            'uri' => '/twig'
        ],
        'defaults' => [
            'destination' => '~/lite-twig',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ],
    'demohunt_ru' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://umicms3',
        'defaults' => [
            'destination' => '~/demohunt',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ],
];