<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin\page;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_CONTROLS => [
        'tree' => [],
        'children' => [],
        'filter' => [],
        'form' => [],
    ],

    AdminComponent::OPTION_INTERFACE => [
        'emptyContext' => [
            'tree' => [
                'controls' => ['tree']
            ],
            'contents' => [
                'controls' => ['filter', 'children']
            ]
        ],
        'selectedContext' => [
            'tree' => [
                'controls' => ['tree']
            ],
            'contents' => [
                'controls' => ['form', 'children']
            ]
        ]
    ],

    AdminComponent::OPTION_CONTROLLERS => [
        'list' => __NAMESPACE__ . '\controller\ListController',
        'item' => __NAMESPACE__ . '\controller\ItemController',
        'action' => __NAMESPACE__ . '\controller\ActionController'
    ],

    AdminComponent::OPTION_ROUTES      => [

        'action' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => 'action'
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
                        'controller' => 'item'
                    ]
                ],
                'list' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route'    => '/{collection}',
                    'defaults' => [
                        'controller' => 'list'
                    ]
                ]
            ]
        ],
    ]
];
