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

    AdminComponent::OPTION_SETTINGS => [],

    AdminComponent::OPTION_CONTROLLERS => [
        'action' => __NAMESPACE__ . '\controller\ActionController'
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

        'viewCounter' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/counter/{counterId:integer}',
            'defaults' => [
                'controller' => 'action',
                'action' => 'viewCounter'
            ],
        ],
    ]
];
