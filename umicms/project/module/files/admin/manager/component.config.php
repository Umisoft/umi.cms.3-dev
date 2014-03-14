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

    AdminComponent::OPTION_SETTINGS => [
        'controls' => [
            [
                'name' => 'fileManager',
                'displayName' => 'Файловый менеджер',
                'action' => '/connector'
            ]
        ],
        'layout' => [
            'emptyContext' => [
                'contents' => [
                    'controls' => ['fileManager']
                ]
            ]
        ]
    ],

    AdminComponent::OPTION_CONTROLLERS => [
        'connector' => __NAMESPACE__ . '\controller\ConnectorController',
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

        'connector' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route'    => '/connector',
            'defaults' => [
                'controller' => 'connector'
            ]
        ]
    ]
];