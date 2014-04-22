<?php

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\settings\SettingsApplication;

return [
    SettingsApplication::OPTION_CLASS => 'umicms\project\admin\settings\SettingsApplication',

    SettingsApplication::OPTION_COMPONENTS => [
        'service' => '{#lazy:~/project/module/service/settings/module.config.php}',
    ],

    SettingsApplication::OPTION_CONTROLLERS => [
        SettingsApplication::ERROR_CONTROLLER   => 'umicms\project\admin\controller\ErrorController',
        'settings' => __NAMESPACE__ . '\controller\ApiSettingsController'
    ],

    SettingsApplication::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'configurator' => [],
            'serviceConfigurator' => ['configurator'],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:settings',
            'component:service',
        ],
        IAclFactory::OPTION_RULES => [

            'configurator' => ['controller:settings' => []],
            'serviceConfigurator' => ['component:service' => []]
        ]
    ],

    SettingsApplication::OPTION_ROUTES => [
        'index' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'settings'
            ],
            'subroutes' => [
                'component' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route' => '/{component}'
                ]
            ]
        ],
    ]
];