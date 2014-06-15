<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\tag;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\CmsPageComponent;

return [

    CmsPageComponent::OPTION_CLASS => 'umicms\project\site\component\CmsPageComponent',
    CmsPageComponent::OPTION_COLLECTION_NAME => 'blogTag',
    CmsPageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\BlogTagRssController'
    ],
    CmsPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\TagWidget',
        'postList' => __NAMESPACE__ . '\widget\TagPostListWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'cloud' => __NAMESPACE__ . '\widget\TagCloudWidget',
        'rssLink' => __NAMESPACE__ . '\widget\TagListRssLinkWidget'
    ],
    CmsPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'rssViewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => [],
                'widget:postList' => [],
                'widget:cloud' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ]
        ]
    ],
    CmsPageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/tag'],
    ],
    CmsPageComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];