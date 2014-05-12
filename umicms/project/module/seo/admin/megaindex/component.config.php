<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\megaindex;

use umi\route\IRouteFactory;
use umicms\project\admin\api\component\DefaultQueryAdminComponent;

return [

    DefaultQueryAdminComponent::OPTION_CLASS => 'umicms\project\admin\api\component\DefaultQueryAdminComponent',
    DefaultQueryAdminComponent::OPTION_MODELS => [
        'umicms\project\module\seo\model\MegaindexModel'
    ],
    DefaultQueryAdminComponent::OPTION_SETTINGS => '{#lazy:~/project/module/seo/configuration/megaindex/model.settings.config.php}',
    DefaultQueryAdminComponent::OPTION_CONTROLLERS => [
        DefaultQueryAdminComponent::SETTINGS_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
        DefaultQueryAdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController'
    ],

    DefaultQueryAdminComponent::OPTION_QUERY_ACTIONS => [
        'siteAnalyze', 'getBacklinks'
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
                'controller' => DefaultQueryAdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];
