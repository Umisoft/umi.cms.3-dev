<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\moderate;

use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PostPageController',
        'edit' => __NAMESPACE__ . '\controller\EditPostController',
        'publish' => __NAMESPACE__ . '\controller\PublishPostController',
        'reject' => __NAMESPACE__ . '\controller\RejectPostController',
        'all' => __NAMESPACE__ . '\controller\PostListController'
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\ModeratePostWidget',
        'ownList' => __NAMESPACE__ . '\widget\OwnListWidget',
        'ownListLink' => __NAMESPACE__ . '\widget\OwnListLinkWidget',
        'allList' => __NAMESPACE__ . '\widget\AllListWidget',
        'allListLink' => __NAMESPACE__ . '\widget\AllListLinkWidget',
        'editPost' => __NAMESPACE__ . '\widget\EditPostWidget',
        'editPostLink' => __NAMESPACE__ . '\widget\EditPostLinkWidget',
        'publishModerate' => __NAMESPACE__ . '\widget\PublishPostWidget',
        'rejectModerate' => __NAMESPACE__ . '\widget\RejectPostWidget'
    ],
    DefaultSitePageComponent::OPTION_ACL => [

    ],
    DefaultSitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/moderate'],
    ],
    DefaultSitePageComponent::OPTION_ROUTES => [
        'all' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/all',
            'defaults' => [
                'controller' => 'all'
            ]
        ],
        'edit' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/edit/{id:integer}',
            'defaults' => [
                'controller' => 'edit'
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