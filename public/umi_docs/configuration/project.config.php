<?php

use umi\extension\twig\TwigTemplateEngine;
use umicms\project\site\SiteApplication;

return  [
    'locales' => [
        'site' => [
            'ru-RU' => [
                'route' => 'default'
            ]
        ],
        'admin' => [
            'ru-RU' => [],
            'en-US' => []
        ]
    ],

    'routes' => [
        'default' => [
            'type' => 'ProjectHostRoute',
            'defaults' => [
                'prefix' => '',
                'locale' => 'ru-RU'
            ]
        ]
    ],

    'defaultLocale' => 'ru-RU',

    SiteApplication::SETTING_DEFAULT_PAGE_GUID => 'd534fd83-0f12-4a0d-9853-583b9181a948',
    SiteApplication::SETTING_DEFAULT_LAYOUT_GUID => 'd6cb8b38-7e2d-4b36-8d15-9fe8947d66c7',

    SiteApplication::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE => TwigTemplateEngine::NAME,
    SiteApplication::SETTING_DEFAULT_TEMPLATE_EXTENSION => 'twig',
];