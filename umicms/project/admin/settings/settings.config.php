<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\settings;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;

return [
    SettingsApplication::OPTION_CLASS => 'umicms\project\admin\settings\SettingsApplication',

    SettingsApplication::OPTION_COMPONENTS => [
        'site' => '{#lazy:~/project/site/settings/component.config.php}',
        'users' => '{#lazy:~/project/module/users/settings/component.config.php}',
        'service' => '{#lazy:~/project/module/service/settings/module.config.php}',
        'seo' => '{#lazy:~/project/module/seo/settings/module.config.php}',
        'statistics' => '{#lazy:~/project/module/statistics/settings/module.config.php}',
        'security' => '{#lazy:~/project/module/security/settings/module.config.php}',
    ],

    SettingsApplication::OPTION_CONTROLLERS => [
        SettingsApplication::ERROR_CONTROLLER   => 'umicms\project\admin\controller\ErrorController',
        'settings' => __NAMESPACE__ . '\controller\SettingsController'
    ],

    SettingsApplication::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'configurator' => [],
            'serviceConfigurator' => ['configurator'],
            'siteConfigurator' => ['configurator'],
            'usersConfigurator' => ['configurator']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:settings',
            'component:service',
            'component:site',
            'component:users',
        ],
        IAclFactory::OPTION_RULES => [

            'configurator' => ['controller:settings' => []],
            'serviceConfigurator' => ['component:service' => []],
            'usersConfigurator' => ['component:users' => []],
            'siteConfigurator' => ['component:site' => []]
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