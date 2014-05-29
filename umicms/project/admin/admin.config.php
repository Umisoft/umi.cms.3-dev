<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;

return [

    AdminApplication::OPTION_CLASS => 'umicms\project\admin\AdminApplication',

    AdminApplication::OPTION_CONTROLLERS => [
        AdminApplication::ERROR_CONTROLLER   => __NAMESPACE__ . '\controller\ErrorController',
        'default' => __NAMESPACE__ . '\controller\DefaultController'
    ],

    AdminApplication::OPTION_COMPONENTS => [
        'api' => '{#lazy:~/project/admin/api/api.config.php}',
        'settings' => '{#lazy:~/project/admin/settings/settings.config.php}',
    ],

    AdminApplication::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'visitor' => [],
            'configurator' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:api',
            'component:settings',
        ],
        IAclFactory::OPTION_RULES => [
            'visitor' => ['component:api' => []],
            'configurator' => ['component:settings' => []]
        ]
    ],

    AdminApplication::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directories' => __DIR__ . '/template/php'
    ],

    AdminApplication::OPTION_ROUTES => [

        'api' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route' => '/api',
            'defaults' => [
                'component' => 'api'
            ]
        ],

        'settings' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/settings',
            'defaults' => [
                'component' => 'settings'
            ]
        ],

        'default' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route' => '.*',
            'defaults' => [
                'controller' => 'default'
            ]
        ]
    ]

];