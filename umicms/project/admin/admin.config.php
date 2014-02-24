<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin;

use umi\route\IRouteFactory;

return [

    AdminApplication::OPTION_CLASS => 'umicms\project\admin\AdminApplication',

    AdminApplication::OPTION_SETTINGS => [

    ],

    AdminApplication::OPTION_CONTROLLERS => [
        AdminApplication::ERROR_CONTROLLER   => __NAMESPACE__ . '\controller\ErrorController',
        AdminApplication::LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController',

        'default' => __NAMESPACE__ . '\controller\DefaultController',
    ],

    AdminApplication::OPTION_COMPONENTS => [
        'structure' => '{#lazy:~/project/module/structure/admin/module.config.php}',
        'users' => '{#lazy:~/project/module/users/admin/module.config.php}',
        'news' => '{#lazy:~/project/module/news/admin/module.config.php}',
        'blog' => '{#lazy:~/project/module/blog/admin/module.config.php}'
    ],

    AdminApplication::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php'
    ],

    AdminApplication::OPTION_ROUTES => [

        'api' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/api/{component}'
        ],

        'default' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{uri}',
            'defaults' => [
                'uri' => '',
                'controller' => 'default'
            ]
        ]
    ]

];