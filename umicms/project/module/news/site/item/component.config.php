<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\item;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\project\site\component\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'newsItem',

    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PageController',
        'rss' => __NAMESPACE__ . '\controller\NewsItemRssController'
    ],

    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\NewsItemWidget',
        'list' => __NAMESPACE__ . '\widget\NewsItemListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\NewsItemListRssLinkWidget'
    ],

    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/news/item']
    ],

    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'rssViewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ]
        ]
    ],

    SitePageComponent::OPTION_ROUTES      => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];