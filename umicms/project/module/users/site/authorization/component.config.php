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
        'index' => __NAMESPACE__ . '\controller\IndexController',
        'logout' => __NAMESPACE__ . '\controller\LogoutController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'loginForm' => __NAMESPACE__ . '\widget\LoginFormWidget',
        'loginLink' => __NAMESPACE__ . '\widget\LoginLinkWidget',
        'logoutForm' => __NAMESPACE__ . '\widget\LogoutFormWidget'
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
                'controller:index' => [],
                'controller:logout' => [],
                'widget:loginForm' => [],
                'widget:logoutForm' => [],
                'widget:loginLink' => []
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
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];