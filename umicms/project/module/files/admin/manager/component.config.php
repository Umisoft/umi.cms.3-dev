<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\files\admin\manager;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_INTERFACE_CONTROLS => [
        'fileManager' => [
            'action' => '/connector'
        ],
    ],

    AdminComponent::OPTION_INTERFACE_LAYOUT => [
        'emptyContext' => [
            'contents' => [
                'controls' => ['fileManager']
            ]
        ]
    ],

    AdminComponent::OPTION_CONTROLLERS => [
        'connector' => __NAMESPACE__ . '\controller\ConnectorController',
        AdminComponent::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\SettingsController'
    ],

    AdminComponent::OPTION_ROUTES      => [

        'connector' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route'    => '/action/connector',
            'defaults' => [
                'controller' => 'connector'
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