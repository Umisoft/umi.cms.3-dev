<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api;

use umi\route\IRouteFactory;

return [

    ApiApplication::OPTION_CLASS => 'umicms\project\admin\api\ApiApplication',

    ApiApplication::OPTION_SETTINGS => [

    ],

    ApiApplication::OPTION_CONTROLLERS => [
        'settings' => __NAMESPACE__ . '\controller\SettingsController',
    ],

    ApiApplication::OPTION_COMPONENTS => [
        'structure' => '{#lazy:~/project/module/structure/admin/module.config.php}',
        'users' => '{#lazy:~/project/module/users/admin/module.config.php}',
        'news' => '{#lazy:~/project/module/news/admin/module.config.php}',
        'blog' => '{#lazy:~/project/module/blog/admin/module.config.php}'
    ],

    ApiApplication::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php'
    ],

    ApiApplication::OPTION_ROUTES => [

        'settings' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route' => '/settings',
            'defaults' => [
                'controller' => 'settings'
            ]
        ],

        'component' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]

];