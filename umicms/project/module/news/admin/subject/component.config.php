<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\subject;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_SETTINGS => [
        'controls' => [
            [
                'name' => 'filter',
                'displayName' => 'Сюжеты'
            ],
            [
                'name' => 'form',
                'displayName' => 'Редактирование'
            ]
        ],
        'layout' => [
            'emptyContext' => [
                'contents' => [
                    'controls' => ['filter']
                ]
            ],
            'selectedContext' => [
                'contents' => [
                    'controls' => ['form']
                ]
            ]
        ]
    ],

    AdminComponent::OPTION_CONTROLLERS => [
        'list' => __NAMESPACE__ . '\controller\ListController',
        'item' => __NAMESPACE__ . '\controller\ItemController',
        'action' => __NAMESPACE__ . '\controller\ActionController',
    ],

    AdminComponent::OPTION_ROUTES      => [

        'settings' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route'    => '/settings',
            'defaults' => [
                'action' => 'settings',
                'controller' => 'action'
            ],
        ],

        'item' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{collection}/{id:integer}',
            'defaults' => [
                'collection' => 'newsSubject',
                'controller' => 'item'
            ],
            'subroutes' => [
                'action' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route'    => '/{action}',
                    'defaults' => [
                        'controller' => 'action'
                    ]
                ]
            ]
        ],
        'list' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{collection}',
            'defaults' => [
                'collection' => 'newsSubject',
                'controller' => 'list'
            ],
            'subroutes' => [
                'action' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route'    => '/{action}',
                    'defaults' => [
                        'controller' => 'action'
                    ]
                ]
            ]
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
