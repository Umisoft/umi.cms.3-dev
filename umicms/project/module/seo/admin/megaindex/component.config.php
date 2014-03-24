<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\megaindex;

use umi\route\IRouteFactory;
use umicms\project\admin\component\SecureAdminComponent;

return [

    SecureAdminComponent::OPTION_CLASS => 'umicms\project\admin\component\SecureAdminComponent',
    SecureAdminComponent::OPTION_MODELS => [
        'umicms\project\module\seo\model\MegaindexModel'
    ],
    SecureAdminComponent::OPTION_SETTINGS => [
        'options' => [
            'login' => 'megaindex@umisoft.ru',
            'password' => 'et676e5rj',
            'siteUrl' => 'umi-cms.ru',
        ]
    ],
    SecureAdminComponent::OPTION_INTERFACE_CONTROLS => [
        'megaindexReport' => [],
    ],
    SecureAdminComponent::OPTION_INTERFACE_LAYOUT => [
        'emptyContext' => [
            'contents' => [
                'controls' => ['megaindexReport']
            ]
        ],
    ],
    SecureAdminComponent::OPTION_CONTROLLERS => [
        SecureAdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',
        SecureAdminComponent::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\SettingsController'
    ],
    SecureAdminComponent::OPTION_ROUTES => [
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
                'controller' => SecureAdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];
