<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\testmodule\admin\test;

use umi\route\IRouteFactory;
use umicms\project\admin\component\SecureAdminComponent;

return [

    SecureAdminComponent::OPTION_CLASS => 'umicms\project\admin\component\SecureAdminComponent',
    SecureAdminComponent::OPTION_CONTROLLERS => [
        SecureAdminComponent::LIST_CONTROLLER => __NAMESPACE__ . '\controller\ListController',
        SecureAdminComponent::SETTINGS_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
        SecureAdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',
        SecureAdminComponent::ITEM_CONTROLLER => __NAMESPACE__ . '\controller\ItemController',
    ],
    SecureAdminComponent::OPTION_ROUTES => [
        'action' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => SecureAdminComponent::ACTION_CONTROLLER
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
                        'controller' => SecureAdminComponent::ITEM_CONTROLLER
                    ]
                ],
                'list' => [
                    'type' => IRouteFactory::ROUTE_SIMPLE,
                    'route' => '/{collection}',
                    'defaults' => [
                        'controller' => SecureAdminComponent::LIST_CONTROLLER
                    ]
                ]
            ]
        ],
        'settings' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => SecureAdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];
