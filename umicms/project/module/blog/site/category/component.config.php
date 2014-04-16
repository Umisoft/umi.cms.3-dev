<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\category;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\blog\site\category\BlogCategoryComponent',
    SiteComponent::OPTION_CONTROLLERS => [
        'category' => __NAMESPACE__ . '\controller\BlogCategoryController',
        'rss' => __NAMESPACE__ . '\controller\BlogCategoryRssController'
    ],
    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ .  '\widget\CategoryWidget',
        'postList' => __NAMESPACE__ . '\widget\CategoryPostListWidget',
        'list' => __NAMESPACE__ .  '\widget\CategoryListWidget',
        'rss' => __NAMESPACE__ .  '\widget\CategoryPostRssUrlWidget'
    ],
    SiteComponent::OPTION_VIEW => [
        'type' => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],
    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'blogCategoryViewer' => [],
            'blogCategoryRssViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:category',
            'controller:rss',
            'widget:view',
            'widget:postList',
            'widget:list',
            'widget:rss'
        ],
        IAclFactory::OPTION_RULES => [
            'blogCategoryViewer' => [
                'controller:category' => [],
                'widget:view' => [],
                'widget:list' => [],
                'widget:postList' => []
            ],
            'blogCategoryRssViewer' => [
                'controller:rss' => [],
                'widget:rss' => []
            ]
        ]
    ],
    SiteComponent::OPTION_ROUTES => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route' => '/rss/(?P<url>.+)',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'category' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route'    => '/(?P<url>.+)',
            'defaults' => [
                'controller' => 'category'
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