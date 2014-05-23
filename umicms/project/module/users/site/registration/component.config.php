<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\registration;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_COMPONENTS => [
        'activation' => '{#lazy:~/project/module/users/site/registration/activation/component.config.php}'
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
        'form' => __NAMESPACE__ . '\widget\FormWidget',
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/users/registration']
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'index' => 'controller:index',
            'link'  => 'widget:link',
            'form'  => 'widget:form',
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => [],
                'widget:link' => [],
                'widget:form' => []
            ]
        ]
    ],

    SiteComponent::OPTION_ROUTES      => [

        'component' => [
            'type' => 'SiteComponentRoute'
        ],

        'index' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{type:string}',
            'defaults' => [
                'type' => AuthorizedUser::TYPE_NAME,
                'controller' => 'index'
            ]
        ]
    ]
];