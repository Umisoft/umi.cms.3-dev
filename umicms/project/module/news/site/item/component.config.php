<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\item;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'newsItem',

    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PageController',
        'rss' => __NAMESPACE__ . '\controller\NewsItemRssController'
    ],

    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\NewsItemWidget',
        'list' => __NAMESPACE__ . '\widget\NewsItemListWidget',
        'rss' => __NAMESPACE__ . '\widget\NewsItemListRssUrlWidget'
    ],

    DefaultSitePageComponent::OPTION_VIEW => [
        'directories' => ['module/news/item']
    ],

    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'rssViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:rss',
            'widget:view',
            'widget:list',
            'widget:rss'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rss' => []
            ]
        ]
    ],

    DefaultSitePageComponent::OPTION_ROUTES      => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];