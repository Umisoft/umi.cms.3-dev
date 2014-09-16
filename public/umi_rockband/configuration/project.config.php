<?php

return [

    'routes' => [
        'en-US' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/rock/en',
                'locale' => 'en-US'
            ]
        ],

        'default' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '/rock',
                'locale' => 'ru-RU'
            ]
        ]
    ]

];