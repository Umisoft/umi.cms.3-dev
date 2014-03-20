<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rubric;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_INTERFACE_CONTROLS => [
        'sideBar' => [],
        'children' => [],
        'filter' => [],
        'form' => [],
    ],

    AdminComponent::OPTION_INTERFACE_LAYOUT => [
        'collection' => 'newsRubric',
        'emptyContext' => [
            'sideBar' => [
                'controls' => ['sideBar']
            ],
            'contents' => [
                'controls' => ['filter', 'children']
            ]
        ],
        'selectedContext' => [
            'sideBar' => [
                'controls' => ['sideBar']
            ],
            'contents' => [
                'controls' => ['form', 'children']
            ]
        ]
    ],

    AdminComponent::OPTION_CONTROLLERS => [
        AdminComponent::LIST_CONTROLLER => __NAMESPACE__ . '\controller\ListController',
        AdminComponent::ITEM_CONTROLLER => __NAMESPACE__ . '\controller\ItemController',
        AdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',

        AdminComponent::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\SettingsController'
    ],

    AdminComponent::OPTION_ROUTES      => [

        'action' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => AdminComponent::ACTION_CONTROLLER
            ]
        ],

        'collection' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route'    => '/collection',
            'subroutes' => [
                'item' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route'    => '/{collection}/{id:integer}',
                    'defaults' => [
                        'controller' => AdminComponent::ITEM_CONTROLLER
                    ]
                ],
                'list' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route'    => '/{collection}',
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
