<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\draft;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\BlogDraftPageController',
        'all' => __NAMESPACE__ . '\controller\BlogDraftListController',
        'edit' => __NAMESPACE__ . '\controller\BlogEditDraftController',
        'published' => __NAMESPACE__ . '\controller\BlogPublishDraftController'
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogDraftWidget',
        'list' => __NAMESPACE__ . '\widget\BlogDraftListWidget',
        'ownList' => __NAMESPACE__ . '\widget\BlogOwnDraftListWidget',
        'ownListUrl' => __NAMESPACE__ . '\widget\BlogOwnDraftListUrlWidget',
        'editDraft' => __NAMESPACE__ . '\widget\BlogEditDraftWidget',
        'publishDraft' => __NAMESPACE__ . '\widget\BlogPublishDraftWidget',
        'editDraftLink' => __NAMESPACE__ . '\widget\BlogEditDraftUrlWidget',
        'allListUrl' => __NAMESPACE__ . '\widget\BlogDraftListUrlWidget'
    ],
    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => ['viewer'],
            'moderator' => ['author']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:all',
            'controller:edit',
            'controller:published',
            'widget:view',
            'widget:list',
            'widget:ownList',
            'widget:ownListUrl',
            'widget:editDraft',
            'widget:publishDraft',
            'widget:editDraftLink',
            'widget:allListUrl',
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:edit' => [
                    'edit' => ['own']
                ],
                'controller:published' => [
                    'publish' => ['own', 'unpublished']
                ],
                'widget:ownList' => [],
                'widget:ownListUrl' => [],
                'widget:editDraft' => [
                    'edit' => ['own']
                ],
                'widget:publishDraft' => [
                    'publish' => ['own', 'unpublished']
                ],
                'widget:editDraftLink' => [
                    'draft' => ['own']
                ],
            ],
            'moderator' => [
                'controller:all' => [],
                'widget:allListUrl' => [],
                'controller:edit' => [
                    'edit' => []
                ],
                'controller:published' => [
                    'publish' => ['unpublished']
                ],
                'widget:ownList' => [],
                'widget:ownListUrl' => [],
                'widget:editDraft' => [],
                'widget:publishDraft' => [
                    'publish' => ['published']
                ],
                'widget:editDraftLink' => [],
            ]
        ]
    ],
    DefaultSitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/draft'],
    ],
    DefaultSitePageComponent::OPTION_ROUTES => [
        'edit' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/edit/{id:integer}',
            'defaults' => [
                'controller' => 'edit'
            ]
        ],
        'published' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/publish/{id:integer}',
            'defaults' => [
                'controller' => 'published'
            ]
        ],
        'all' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route' => '/all',
            'defaults' => [
                'controller' => 'all'
            ]
        ]
    ]
];