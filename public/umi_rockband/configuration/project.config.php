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
    ],

    'defaultPage' => '002675ac-9e29-4675-abf7-aa0f93ff9a8c',
    'defaultLayout' => 'd6cb8b38-7e2d-4b36-8d15-9fe8947d66c7',
    'defaultTemplatingEngineType' => 'xslt',
    'defaultTemplateExtension' => 'xsl',

];