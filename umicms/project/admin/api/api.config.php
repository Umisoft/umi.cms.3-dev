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
        ApiApplication::ERROR_CONTROLLER   => __NAMESPACE__ . '\controller\ErrorController',
        'settings' => __NAMESPACE__ . '\controller\SettingsController',
    ],

    ApiApplication::OPTION_COMPONENTS => [

        'blog' => '{#lazy:~/project/module/blog/admin/module.config.php}',
        'service' => '{#lazy:~/project/module/service/admin/module.config.php}',
        'files' => '{#lazy:~/project/module/files/admin/module.config.php}',
        'models' => '{#lazy:~/project/module/models/admin/module.config.php}',
        'news' => '{#lazy:~/project/module/news/admin/module.config.php}',
        'seo' => '{#lazy:~/project/module/seo/admin/module.config.php}',
        'search' => '{#lazy:~/project/module/search/admin/module.config.php}',
        'statistics' => '{#lazy:~/project/module/statistics/admin/module.config.php}',
        'structure' => '{#lazy:~/project/module/structure/admin/module.config.php}',
        'users' => '{#lazy:~/project/module/users/admin/module.config.php}'
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