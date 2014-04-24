<?php

use umi\route\IRouteFactory;

return [
    'lite-xslt' => [
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

    'lite-twig' => [
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

    'lite-php-en' => [
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
    'lite-php-ru' => [
        'type' => IRouteFactory::ROUTE_EXTENDED,
        'route' => 'http://realloc.srv09.megaserver.umisoft.ru',
        'defaults' => [
            'destination' => '~/lite-php',
            'config' => '~/project/project.config.php',
            'locale' => 'ru-RU'
        ]
    ],

];