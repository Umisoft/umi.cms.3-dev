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
use umicms\project\site\component\CmsHierarchicPageComponent;

return [

    CmsHierarchicPageComponent::OPTION_CLASS => 'umicms\project\site\component\CmsHierarchicPageComponent',
    CmsHierarchicPageComponent::OPTION_COLLECTION_NAME => 'blogCategory',
    CmsHierarchicPageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\BlogCategoryRssController'
    ],
    CmsHierarchicPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\CategoryWidget',
        'postList' => __NAMESPACE__ . '\widget\CategoryPostListWidget',
        'list' => __NAMESPACE__ . '\widget\CategoryListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\CategoryPostRssLinkWidget'
    ],
    CmsHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/category'],
    ],
    CmsHierarchicPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
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
    CmsHierarchicPageComponent::OPTION_ROUTES => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route' => '/rss/(?P<url>.+)',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];