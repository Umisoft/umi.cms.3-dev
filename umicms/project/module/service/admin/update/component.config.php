<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin\update;

use umi\route\IRouteFactory;
use umicms\hmvc\component\admin\AdminComponent;

return [
    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\AdminComponent',

    AdminComponent::OPTION_CONTROLLERS => [
        AdminComponent::INTERFACE_LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController',
        AdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController'
    ],

    AdminComponent::OPTION_QUERY_ACTIONS => [
        'update' => []
    ],

    AdminComponent::OPTION_ROUTES => [
        'action' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/action/{action}',
            'defaults' => [
                'controller' => AdminComponent::ACTION_CONTROLLER,
            ],
        ],

        'interface' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => AdminComponent::INTERFACE_LAYOUT_CONTROLLER
            ]
        ]
    ]
];
