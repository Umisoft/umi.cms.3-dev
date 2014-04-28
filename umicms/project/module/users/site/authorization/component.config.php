<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\authorization;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_CONTROLLERS => [
        'login' => __NAMESPACE__ . '\controller\LoginController',
        'logout' => __NAMESPACE__ . '\controller\LogoutController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'loginForm' => __NAMESPACE__ . '\widget\LoginFormWidget',
        'loginLink' => __NAMESPACE__ . '\widget\LoginLinkWidget',
        'logoutLink' => __NAMESPACE__ . '\widget\LogoutLinkWidget'
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/users/authorization']
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'index' => 'controller:login',
            'loginForm'  => 'widget:loginForm',
            'loginLink'  => 'widget:loginLink',
            'logoutLink'  => 'widget:logoutLink'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:login' => [],
                'widget:loginForm' => [],
                'widget:loginLink' => [],
                'widget:logoutLink' => []
            ]
        ]
    ],

    SiteComponent::OPTION_ROUTES      => [
        'logout' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/logout',
            'defaults' => [
                'controller' => 'logout'
            ]
        ],
        'login' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'login'
            ]
        ]
    ]
];