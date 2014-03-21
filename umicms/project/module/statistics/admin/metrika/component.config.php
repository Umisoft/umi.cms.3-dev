<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\statistics\admin\metrika;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',
    AdminComponent::OPTION_INTERFACE_CONTROLS => [
        'counters' => [],
        'counter' => [],
    ],
    AdminComponent::OPTION_INTERFACE_LAYOUT => [
        'emptyContext' => [
            'contents' => [
                'controls' => ['counters']
            ]
        ],
        'selectedContext' => [
            'contents' => [
                'controls' => ['counter']
            ]
        ]
    ],
    AdminComponent::OPTION_CONTROLLERS => [

        AdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',
        AdminComponent::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\SettingsController'
    ],
    AdminComponent::OPTION_ROUTES => [
        'action' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/action/{action}',
            'defaults' => [
                'controller' => 'action',
            ],
        ],

        'settings' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => AdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];
