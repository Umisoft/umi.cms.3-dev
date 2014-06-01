<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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