<?php

use umi\extension\twig\TwigTemplateEngine;
use umicms\project\site\SiteApplication;

return [

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

    SiteApplication::SETTING_DEFAULT_LAYOUT_GUID => 'd6cb8b38-7e2d-4b36-8d15-9fe8947d66c7',

    SiteApplication::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE => TwigTemplateEngine::NAME,
    SiteApplication::SETTING_DEFAULT_TEMPLATE_EXTENSION => 'twig',
];