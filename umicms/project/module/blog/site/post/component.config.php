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
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\blog\site\post\BlogPostComponent',
    SiteComponent::OPTION_CONTROLLERS => [
        'post' => __NAMESPACE__ . '\controller\BlogPostController',
        'rss' => __NAMESPACE__ . '\controller\BlogPostRssController'
    ],
    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogPostWidget',
        'list' => __NAMESPACE__ . '\widget\BlogPostListWidget',
        'rss' => __NAMESPACE__ . '\widget\BlogPostListRssUrlWidget'
    ],
    SiteComponent::OPTION_VIEW => [
        'type' => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],
    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'blogPostViewer' => [],
            'blogPostRssViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:item',
            'controller:rss',
            'widget:view',
            'widget:list',
            'widget:rss'
        ],
        IAclFactory::OPTION_RULES => [
            'blogPostViewer' => [
                'controller:item' => [],
                'widget:view' => [],
                'widget:list' => []
            ],
            'blogPostRssViewer' => [
                'controller:rss' => [],
                'widget:rss' => []
            ]
        ]
    ],
    SiteComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'post' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{slug}',
            'defaults' => [
                'controller' => 'post'
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