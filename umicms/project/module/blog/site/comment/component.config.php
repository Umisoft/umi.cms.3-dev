<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\comment;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSiteHierarchicPageComponent;

return [

    DefaultSiteHierarchicPageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSiteHierarchicPageComponent',
    DefaultSiteHierarchicPageComponent::OPTION_COLLECTION_NAME => 'blogComment',
    DefaultSiteHierarchicPageComponent::OPTION_CONTROLLERS => [
        'add' => __NAMESPACE__ . '\controller\AddController',
        'publish' => __NAMESPACE__ . '\controller\PublishController',
        'reject' => __NAMESPACE__ . '\controller\RejectController',
    ],
    DefaultSiteHierarchicPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\CommentWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'add' => __NAMESPACE__ . '\widget\AddWidget',
        'publish' => __NAMESPACE__ . '\widget\PublishWidget',
        'reject' => __NAMESPACE__ . '\widget\RejectWidget'
    ],
    DefaultSiteHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/comment'],
    ],
    DefaultSiteHierarchicPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'poster' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:add',
            'controller:publish',
            'controller:reject',
            'widget:view',
            'widget:list',
            'widget:add',
            'widget:publish',
            'widget:reject'
        ],
        IAclFactory::OPTION_RULES => [
            'blogPostViewer' => [
                'controller:item' => [],
                'widget:view' => [],
                'widget:list' => []
            ]
        ]
    ],
    DefaultSiteHierarchicPageComponent::OPTION_ROUTES => [
        'add' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/add/{parent:integer}',
            'defaults' => [
                'controller' => 'add',
                'parent' => null
            ]
        ],
        'publish' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/publish/{id:integer}',
            'defaults' => [
                'controller' => 'publish'
            ]
        ],
        'reject' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/reject/{id:integer}',
            'defaults' => [
                'controller' => 'reject'
            ]
        ]
    ]
];