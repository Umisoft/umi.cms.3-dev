<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\files\admin\manager;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_CONTROLLERS => [
        'connector' => __NAMESPACE__ . '\controller\ConnectorController',
        AdminComponent::COMPONENT_LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController',
    ],

    AdminComponent::OPTION_MODIFY_ACTIONS => [
        'connector'
    ],

    AdminComponent::OPTION_ROUTES      => [

        'action'     => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => 'connector'
            ]
        ],

        'settings' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => AdminComponent::COMPONENT_LAYOUT_CONTROLLER
            ]
        ]
    ]
];