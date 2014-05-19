<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\post;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PageController',
        'add' => __NAMESPACE__ . '\controller\PostAddController',
        'edit' => __NAMESPACE__ . '\controller\PostEditController',
        'unPublished' => __NAMESPACE__ . '\controller\PostToDraftController',
        'rss' => __NAMESPACE__ . '\controller\PostRssController'
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\PostWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'rss' => __NAMESPACE__ . '\widget\ListRssUrlWidget',
        'add' => __NAMESPACE__ . '\widget\AddWidget',
        'edit' => __NAMESPACE__ . '\widget\EditWidget',
        'unPublished' => __NAMESPACE__ . '\widget\PostToDraftWidget',
        'editPostLink' => __NAMESPACE__ . '\widget\EditUrlWidget'
    ],
    DefaultSitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/post'],
    ],
    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'rssViewer' => [],
            'viewer' => [],
            'author' => ['viewer'],
            'publisher' => ['author'],
            'moderator' => ['publisher']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:rss',
            'controller:add',
            'controller:edit',
            'controller:unPublished',
            'widget:view',
            'widget:list',
            'widget:rss',
            'widget:add',
            'widget:editPost',
            'widget:unPublished',
            'widget:editPostLink',
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rss' => []
            ],
            'author' => [
                'controller:unPublished' => [],
                'widget:unPublished' => [],
                'model:blogPost' => [
                    'unPublished' => ['own']
                ]
            ],
            'publisher' => [
                'controller:add' => [],
                'widget:add' => []
            ],
            'moderator' => [
                'controller:edit' => [],
                'controller:unPublished' => [],
                'widget:editPost' => [],
                'widget:unPublished' => [],
                'widget:editPostLink' => [],
                'model:blogPost' => [
                    'edit' => []
                ]
            ]
        ]
    ],
    DefaultSitePageComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'add' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route' => '/add',
            'defaults' => [
                'controller' => 'add'
            ]
        ],
        'unPublished' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/unPublish/{id:integer}',
            'defaults' => [
                'controller' => 'unPublished'
            ]
        ],
        'edit' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/edit/{id:integer}',
            'defaults' => [
                'controller' => 'edit'
            ]
        ]
    ]
];