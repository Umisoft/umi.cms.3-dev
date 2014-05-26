<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\profile;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],

    SiteComponent::OPTION_COMPONENTS => [
        'password' => '{#lazy:~/project/module/users/site/profile/password/component.config.php}'
    ],

    SiteComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
        'view' => __NAMESPACE__ . '\widget\ViewWidget',
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/users/profile']
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'index' => 'controller:index',
            'link'  => 'widget:link',
            'view'  => 'widget:view',
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => [],
                'widget:link' => [],
                'widget:view' => []
            ]
        ]
    ],

    SiteComponent::OPTION_ROUTES      => [

        'component' => [
            'type' => 'SiteComponentRoute'
        ],

        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];