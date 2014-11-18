<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\authorization;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',

    SiteComponent::OPTION_CONTROLLERS => [
        'login' => __NAMESPACE__ . '\controller\LoginController',
        'logout' => __NAMESPACE__ . '\controller\LogoutController',
        'loginByAuthCookie' => __NAMESPACE__ . '\controller\LoginByAuthCookieController'
    ],

    SiteComponent::OPTION_WIDGET => [
        'loginForm' => __NAMESPACE__ . '\widget\LoginFormWidget',
        'loginLink' => __NAMESPACE__ . '\widget\LoginLinkWidget',
        'logoutForm' => __NAMESPACE__ . '\widget\LogoutFormWidget',
        'loginByCookieLink' => __NAMESPACE__ . '\widget\LoginByAuthCookieLinkWidget',
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/users/authorization']
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:login' => [],
                'controller:logout' => [],
                'controller:loginByAuthCookie' => [],
                'widget:loginForm' => [],
                'widget:logoutForm' => [],
                'widget:loginLink' => [],
                'widget:loginByCookieLink' => [],
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
        'loginByAuthCookie' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/login-by-auth-cookie',
            'defaults' => [
                'controller' => 'loginByAuthCookie'
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