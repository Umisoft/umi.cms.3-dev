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
use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',
    SiteGroupComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\hmvc\component\site\SiteStructurePageController',
        'rss' => __NAMESPACE__ . '\controller\RssController',
    ],
    SiteGroupComponent::OPTION_COMPONENTS => [
        'profile' => '{#lazy:~/project/module/blog/site/author/profile/component.config.php}',
        'view' => '{#lazy:~/project/module/blog/site/author/view/component.config.php}'
    ],
    SiteGroupComponent::OPTION_WIDGET => [
        'profile' => __NAMESPACE__ . '\widget\AuthorProfileWidget',
        'view' => __NAMESPACE__ . '\widget\AuthorViewWidget',
        'rss' => __NAMESPACE__ . '\widget\RssLinkWidget',
    ],
    SiteGroupComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:profile' => [],
                'widget:view' => []
            ]
        ]
    ],
    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/blog/author'],
    ],
    SiteGroupComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'component' => [
            'type' => 'SiteComponentRoute'
        ],
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];