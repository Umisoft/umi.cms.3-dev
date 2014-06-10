<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate;

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
        'publish' => __NAMESPACE__ . '\controller\PostPublishController',
        'reject' => __NAMESPACE__ . '\controller\PostRejectController',
        'draft' => __NAMESPACE__ . '\controller\PostDraftController',
        'all' => __NAMESPACE__ . '\controller\PostListController'
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\PostWidget',
        'ownList' => __NAMESPACE__ . '\widget\OwnListWidget',
        'ownListLink' => __NAMESPACE__ . '\widget\OwnListLinkWidget',
        'allList' => __NAMESPACE__ . '\widget\AllListWidget',
        'allListLink' => __NAMESPACE__ . '\widget\AllListLinkWidget',
        'editPostLink' => __NAMESPACE__ . '\widget\PostEditLinkWidget',
        'publishModerate' => __NAMESPACE__ . '\widget\PostPublishWidget',
        'rejectModerate' => __NAMESPACE__ . '\widget\PostRejectWidget',
        'draftModerate' => __NAMESPACE__ . '\widget\PostDraftWidget'
    ],
    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'moderator' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:edit',
            'controller:publish',
            'controller:reject',
            'controller:draft',
            'controller:all',
            'widget:view',
            'widget:ownList',
            'widget:ownListLink',
            'widget:allList',
            'widget:allListLink',
            'widget:editPostLink',
            'widget:publishModerate',
            'widget:rejectModerate',
            'widget:draftModerate',
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:index' => [],
                'controller:draft' => [],
                'widget:draftModerate' => [],
                'widget:view' => [],
                'controller:page' => [],
                'widget:ownList' => [],
                'widget:ownListLink' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'moderator' => [
                'controller:index' => [],
                'controller:page' => [],
                'controller:edit' => [],
                'controller:publish' => [],
                'controller:reject' => [],
                'controller:all' => [],
                'widget:view' => [],
                'widget:allList' => [],
                'widget:allListLink' => [],
                'widget:editPostLink' => [],
                'widget:publishModerate' => [],
                'widget:rejectModerate' => [],
                'model:blogPost' => []
            ]
        ]
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
        ],
        'draft' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/draft/{id:integer}',
            'defaults' => [
                'controller' => 'draft'
            ]
        ]
    ]
];