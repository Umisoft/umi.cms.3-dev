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
        'rest' => '{#lazy:~/project/admin/rest/rest.config.php}',
    ],

    AdminApplication::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directories' => dirname(__DIR__) . '/template/php'
    ],

    AdminApplication::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => ['controller:default' => []]
        ]
    ],

    AdminApplication::OPTION_ROUTES => [

        'rest' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route' => '/rest',
            'defaults' => [
                'component' => 'rest'
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