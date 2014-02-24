<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\item;

use umi\hmvc\component\IComponent;
use umi\route\IRouteFactory;

return [

    IComponent::OPTION_CONTROLLERS => [
        'settings' => __NAMESPACE__ . '\controller\SettingsController',
        'list' => __NAMESPACE__ . '\controller\ListController',
        'item' => __NAMESPACE__ . '\controller\ItemController',
    ],

    IComponent::OPTION_ROUTES      => [

        'settings' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route'    => '/settings',
            'defaults' => [
                'controller' => 'settings'
            ]
        ],

        'item' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{controller}/{guid:guid}'
        ],

        'list' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{controller}'
        ]
    ]
];