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
use umicms\hmvc\component\site\SiteHierarchicPageComponent;

return [

    SiteHierarchicPageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteHierarchicPageComponent',
    SiteHierarchicPageComponent::OPTION_COLLECTION_NAME => 'blogCategory',
    SiteHierarchicPageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\RssController'
    ],
    SiteHierarchicPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\CategoryWidget',
        'postList' => __NAMESPACE__ . '\widget\PostListWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\RssLinkWidget'
    ],
    SiteHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/category'],
    ],
    SiteHierarchicPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'rssViewer' => []
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
    SiteHierarchicPageComponent::OPTION_ROUTES => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route' => '/rss/(?P<url>.+)',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];