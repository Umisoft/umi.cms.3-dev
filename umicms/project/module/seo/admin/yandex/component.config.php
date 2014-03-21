<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\yandex;

use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',
    AdminComponent::OPTION_MODELS => [
        'umicms\project\module\seo\model\YandexModel'
    ],
    AdminComponent::OPTION_SETTINGS => [
        'options' => [
            'hostId' => '3478487',
            'oauthToken' => '26ccbadbc7494266a7a0b2981a47d27d',
        ]
    ],
    AdminComponent::OPTION_INTERFACE_CONTROLS => [
        'yandexWebmasterReport' => [],
    ],
    AdminComponent::OPTION_INTERFACE_LAYOUT => [
        'emptyContext' => [
            'contents' => [
                'controls' => ['yandexWebmasterReport']
            ]
        ],
    ],
    AdminComponent::OPTION_CONTROLLERS => [
        AdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',
        AdminComponent::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\SettingsController'
    ],
    AdminComponent::OPTION_ROUTES => [
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
                'controller' => AdminComponent::SETTINGS_CONTROLLER
            ]
        ]
    ]
];
