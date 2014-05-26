<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\author;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'blogAuthor',
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\BlogAuthorRssController',
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogAuthorWidget',
        'list' => __NAMESPACE__ . '\widget\BlogAuthorListWidget',
        'postList' => __NAMESPACE__ . '\widget\BlogAuthorPostListWidget',
        'rss' => __NAMESPACE__ . '\widget\BlogAuthorListRssLinkWidget'
    ],
    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'rssViewer' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:rss',
            'widget:view',
            'widget:list',
            'widget:postList',
            'widget:rss',
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => [],
                'widget:postList' => [],
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rss' => []
            ]
        ]
    ],
    DefaultSitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/author'],
    ],
    DefaultSitePageComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];