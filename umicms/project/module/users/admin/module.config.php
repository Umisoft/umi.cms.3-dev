<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\admin;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_SETTINGS => [],

    AdminComponent::OPTION_COMPONENTS => [
        'user' => '{#lazy:~/project/module/users/admin/user/component.config.php}'
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ],

        'trash' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route'    => '/trash',
            'defaults' => [
                'action' => 'trash',
                'controller' => 'action'
            ],
        ],

        'untrash' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route'    => '/untrash',
            'defaults' => [
                'action' => 'untrash',
                'controller' => 'action'
            ],
        ],

        'emptyTrash' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route'    => '/emptyTrash',
            'defaults' => [
                'action' => 'emptyTrash',
                'controller' => 'action'
            ],
        ],
    ]
];
