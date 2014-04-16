<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\testmodule\admin\test;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',
    AdminComponent::OPTION_CONTROLLERS => [
        AdminComponent::LIST_CONTROLLER => __NAMESPACE__ . '\controller\ListController',
        AdminComponent::SETTINGS_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
        AdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',
        AdminComponent::ITEM_CONTROLLER => __NAMESPACE__ . '\controller\ItemController',
    ],
    AdminComponent::OPTION_ROUTES => [
        'action' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => AdminComponent::ACTION_CONTROLLER
            ]
        ],
        'collection' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/collection',
            'subroutes' => [
                'item' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route'    => '/{collection}/{id:integer}',
                    'defaults' => [
                        'controller' => AdminComponent::ITEM_CONTROLLER
                    ]
                ],
                'list' => [
                    'type' => IRouteFactory::ROUTE_SIMPLE,
                    'route' => '/{collection}',
                    'defaults' => [
                        'controller' => AdminComponent::LIST_CONTROLLER
                    ]
                ]
            ]
        ],
        'settings' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => AdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];
