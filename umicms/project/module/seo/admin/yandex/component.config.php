<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\yandex;

use umi\route\IRouteFactory;
use umicms\project\admin\api\component\DefaultQueryAdminComponent;

return [

    DefaultQueryAdminComponent::OPTION_CLASS => 'umicms\project\admin\api\component\DefaultQueryAdminComponent',
    DefaultQueryAdminComponent::OPTION_MODELS => [
        'umicms\project\module\seo\model\YandexModel'
    ],
    DefaultQueryAdminComponent::OPTION_SETTINGS => '{#lazy:~/project/module/seo/configuration/yandex/model.settings.config.php}',
    DefaultQueryAdminComponent::OPTION_CONTROLLERS => [
        DefaultQueryAdminComponent::COMPONENT_LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
        DefaultQueryAdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController'
    ],

    DefaultQueryAdminComponent::OPTION_QUERY_ACTIONS => [
        'hosts', 'host', 'indexed', 'links', 'tops'
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
                'controller' => DefaultQueryAdminComponent::COMPONENT_LAYOUT_CONTROLLER
            ]
        ]
    ]
];
