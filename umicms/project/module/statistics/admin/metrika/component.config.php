<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\statistics\admin\metrika;

use umi\route\IRouteFactory;
use umicms\project\admin\api\component\DefaultQueryAdminComponent;

return [
    DefaultQueryAdminComponent::OPTION_CLASS => 'umicms\project\admin\api\component\DefaultQueryAdminComponent',
    DefaultQueryAdminComponent::OPTION_SETTINGS => '{#lazy:~/project/module/statistics/configuration/metrika/model.settings.config.php}',
    DefaultQueryAdminComponent::OPTION_CONTROLLERS => [
        DefaultQueryAdminComponent::INTERFACE_LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
        DefaultQueryAdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController'
    ],

    DefaultQueryAdminComponent::OPTION_QUERY_ACTIONS => [
        'counter', 'counters', 'navigation'
    ],

    DefaultQueryAdminComponent::OPTION_ROUTES => [
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
                'controller' => DefaultQueryAdminComponent::INTERFACE_LAYOUT_CONTROLLER
            ]
        ]
    ]
];
