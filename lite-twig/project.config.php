<?php

use umi\dbal\toolbox\DbalTools;
use umi\hmvc\component\IComponent;
use umi\i18n\toolbox\I18nTools;
use umi\route\IRouteFactory;
use umicms\Bootstrap;

return [
    Bootstrap::OPTION_TOOLS_SETTINGS => [
        DbalTools::NAME => [
            'servers' => require (__DIR__ . '/../configuration/db.config.php')
        ],
        I18nTools::NAME => [
            'defaultLocale' => 'ru-RU'
        ]
    ],

    IComponent::OPTION_CONTROLLERS => [
        'install' =>   'project\install\controller\InstallController'
    ],

    IComponent::OPTION_ROUTES => [
        'install' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'priority' => 0,
            'route' => '/install',
            'defaults' => [
                'controller' => 'install'
            ]
        ]
    ]

];