<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\service\admin\backup;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',
    AdminComponent::OPTION_CONTROLLERS => [
        AdminComponent::LIST_CONTROLLER => __NAMESPACE__ . '\controller\ListController',
        AdminComponent::SETTINGS_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
    ],
    AdminComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'editor' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:settings',
            'controller:list'
        ],
        IAclFactory::OPTION_RULES => [
            'editor' => [
                'controller:settings' => [],
                'controller:list' => []
            ],
        ]
    ],
    AdminComponent::OPTION_ROUTES => [
        'collection' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/collection',
            'subroutes' => [
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
