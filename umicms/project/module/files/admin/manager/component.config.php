<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\files\admin\manager;

use umi\route\IRouteFactory;
use umicms\project\admin\component\SecureAdminComponent;

return [

    SecureAdminComponent::OPTION_CLASS => 'umicms\project\admin\component\SecureAdminComponent',

    SecureAdminComponent::OPTION_CONTROLLERS => [
        'connector' => __NAMESPACE__ . '\controller\ConnectorController',
        SecureAdminComponent::SETTINGS_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
    ],

    SecureAdminComponent::OPTION_ROUTES      => [

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
                'controller' => SecureAdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];