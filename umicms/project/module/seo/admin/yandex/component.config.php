<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\yandex;

use umi\route\IRouteFactory;
use umicms\project\admin\component\SecureAdminComponent;

return [

    SecureAdminComponent::OPTION_CLASS => 'umicms\project\admin\component\SecureAdminComponent',
    SecureAdminComponent::OPTION_MODELS => [
        'umicms\project\module\seo\model\YandexModel'
    ],
    SecureAdminComponent::OPTION_SETTINGS => [
        'options' => [
            'hostId' => '3478487',
            'oauthToken' => '26ccbadbc7494266a7a0b2981a47d27d',
        ]
    ],
    SecureAdminComponent::OPTION_INTERFACE_CONTROLS => [
        /*'chart' => [],
        'counters' => [],
        'accordion' => [],*/
    ],
    SecureAdminComponent::OPTION_INTERFACE_LAYOUT => [
        'emptyContext' => [
            /*'tree' => [
                'controls' => ['accordion']
            ],
            'contents' => [
                'controls' => ['counters']
            ]*/
        ],
        'selectedContext' => [
            /*'contents' => [
                'controls' => ['chart', 'accordion']
            ]*/
        ]
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
