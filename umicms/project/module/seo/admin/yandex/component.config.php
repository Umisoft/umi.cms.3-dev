<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\yandex;

use umi\route\IRouteFactory;
use umicms\project\admin\api\component\DefaultQueryAdminComponent;

return [

    DefaultQueryAdminComponent::OPTION_CLASS => 'umicms\project\admin\api\component\DefaultQueryAdminComponent',
    DefaultQueryAdminComponent::OPTION_MODELS => [
        'umicms\project\module\seo\model\YandexModel'
    ],
    DefaultQueryAdminComponent::OPTION_SETTINGS => [
        'options' => [
            'hostId' => '3478487',
            'oauthToken' => '26ccbadbc7494266a7a0b2981a47d27d',
        ]
    ],
    DefaultQueryAdminComponent::OPTION_CONTROLLERS => [
        DefaultQueryAdminComponent::SETTINGS_CONTROLLER => __NAMESPACE__ . '\controller\SettingsController',
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
                'controller' => DefaultQueryAdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];
