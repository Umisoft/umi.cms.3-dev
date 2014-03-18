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

    AdminComponent::OPTION_CONTROLS => [
        'chart' => [],
        'table' => [],
    ],

    AdminComponent::OPTION_INTERFACE => [
        'emptyContext' => [
            'contents' => [
                'controls' => ['table']
            ]
        ],
        'selectedContext' => [
            'contents' => [
                'controls' => ['chart']
            ]
        ]
    ],

    AdminComponent::OPTION_CONTROLLERS => [
        'action' => __NAMESPACE__ . '\controller\ActionController'
    ],

    AdminComponent::OPTION_ROUTES      => [
        'action' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => 'action',
            ],
        ],
    ],
];
