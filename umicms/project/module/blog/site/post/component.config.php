<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PageController',
        'add' => __NAMESPACE__ . '\controller\PostAddController',
        'edit' => __NAMESPACE__ . '\controller\PostEditController',
        'unPublished' => __NAMESPACE__ . '\controller\PostToDraftController',
        'rss' => __NAMESPACE__ . '\controller\PostRssController'
    ],
    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\PostWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\ListRssLinkWidget',
        'unPublished' => __NAMESPACE__ . '\widget\PostToDraftWidget',
        'addPostLink' => __NAMESPACE__ . '\widget\AddLinkWidget',
        'editPostLink' => __NAMESPACE__ . '\widget\EditLinkWidget'
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/post'],
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'rssViewer' => [],
            'viewer' => [],
            'author' => ['viewer'],
            'moderator' => ['author']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ],
            'author' => [
                'widget:addPostLink' => [],
                'widget:unPublished' => [],
                'controller:add' => [],
                'controller:unPublished' => [],
                'model:blogPost' => [
                    'unPublished' => ['own']
                ]
            ],
            'moderator' => [
                'controller:index' => [],
                'controller:page' => [],
                'controller:edit' => [],
                'controller:unPublished' => [],
                'widget:editPostLink' => [],
                'model:blogPost' => []
            ]
        ]
    ],
    SitePageComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'add' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/add/{id:integer}',
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