<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogAuthor',
    SitePageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\BlogAuthorRssController',
        'edit' => __NAMESPACE__ . '\controller\ProfileEditController',
    ],
    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogAuthorWidget',
        'list' => __NAMESPACE__ . '\widget\BlogAuthorListWidget',
        'editProfileLink' => __NAMESPACE__ . '\widget\EditLinkWidget',
        'postList' => __NAMESPACE__ . '\widget\BlogAuthorPostListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\BlogAuthorListRssLinkWidget'
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'viewer' => [],
            'rssViewer' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => [],
                'widget:postList' => [],
            ],
            'author' => [
                'controller:edit' => [],
                'widget:editProfileLink' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ]
        ]
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/author'],
    ],
    SitePageComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'edit' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/edit',
            'defaults' => [
                'controller' => 'edit'
            ]
        ]
    ]
];