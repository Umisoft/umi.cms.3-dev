<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\category;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSiteHierarchicPageComponent;

return [

    DefaultSiteHierarchicPageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSiteHierarchicPageComponent',
    DefaultSiteHierarchicPageComponent::OPTION_COLLECTION_NAME => 'blogCategory',
    DefaultSiteHierarchicPageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\BlogCategoryRssController'
    ],
    DefaultSiteHierarchicPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\CategoryWidget',
        'postList' => __NAMESPACE__ . '\widget\CategoryPostListWidget',
        'list' => __NAMESPACE__ . '\widget\CategoryListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\CategoryPostRssLinkWidget'
    ],
    DefaultSiteHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/category'],
    ],
    DefaultSiteHierarchicPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'rssViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:rss',
            'widget:view',
            'widget:postList',
            'widget:list',
            'widget:rssLink'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => [],
                'widget:postList' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ]
        ]
    ],
    DefaultSiteHierarchicPageComponent::OPTION_ROUTES => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route' => '/rss/(?P<url>.+)',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];