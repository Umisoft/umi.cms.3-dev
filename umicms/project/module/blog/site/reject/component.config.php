<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\reject;

use umi\acl\IAclFactory;
use umi\acl\IAclManager;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PostPageController',
        'edit' => __NAMESPACE__ . '\controller\PostEditController',
        'sendToModeration' => __NAMESPACE__ . '\controller\PostSendToModerationController',
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\PostWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'listLink' => __NAMESPACE__ . '\widget\ListLinkWidget',
        'editPost' => __NAMESPACE__ . '\widget\PostEditWidget',
        'editPostLink' => __NAMESPACE__ . '\widget\PostEditLinkWidget',
        'sendToModeration' => __NAMESPACE__ . '\widget\PostSendToModerationWidget'

    ],
    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:edit',
            'controller:sendToModeration',
            'widget:view',
            'widget:list',
            'widget:listLink',
            'widget:editPost',
            'widget:editPostLink',
            'widget:sendToModeration',
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:index' => [],
                'controller:page' => [],
                'controller:edit' => [],
                'controller:sendToModeration' => [],
                'widget:view' => [],
                'widget:list' => [],
                'widget:listLink' => [],
                'widget:editPost' => [],
                'widget:editPostLink' => [],
                'widget:sendToModeration' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ]
        ]
    ],
    DefaultSitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/reject'],
    ],
    DefaultSitePageComponent::OPTION_ROUTES => [
        'edit' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/edit/{id:integer}',
            'defaults' => [
                'controller' => 'edit'
            ]
        ],
        'sendToModeration' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/sendToModeration/{id:integer}',
            'defaults' => [
                'controller' => 'sendToModeration'
            ]
        ]
    ]
];