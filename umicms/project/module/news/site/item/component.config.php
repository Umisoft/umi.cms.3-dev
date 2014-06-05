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
        'rssLink' => __NAMESPACE__ . '\widget\NewsItemListRssLinkWidget'
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
            'widget:rssLink'
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