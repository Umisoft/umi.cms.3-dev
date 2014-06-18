<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteGroupComponent;

return [
    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',
    SiteGroupComponent::OPTION_CONTROLLERS => [
        'unPublished' => __NAMESPACE__ . '\controller\DraftController',
        'rss' => __NAMESPACE__ . '\controller\RssController'
    ],
    SiteGroupComponent::OPTION_COMPONENTS => [
        'add' => '{#lazy:~/project/module/blog/site/post/add/component.config.php}',
        'edit' => '{#lazy:~/project/module/blog/site/post/edit/component.config.php}',
        'view' => '{#lazy:~/project/module/blog/site/post/view/component.config.php}',
    ],
    SiteGroupComponent::OPTION_WIDGET => [
        'rssLink' => __NAMESPACE__ . '\widget\RssLinkWidget',
        'unPublished' => __NAMESPACE__ . '\widget\DraftWidget',
    ],
    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/blog/post'],
    ],
    SiteGroupComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'rssViewer' => [],
            'author' => [],
            'moderator' => ['author']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ],
            'author' => [
                'widget:unPublished' => [],
                'controller:unPublished' => [],
                'model:blogPost' => [
                    'unPublished' => ['own']
                ]
            ],
            'moderator' => [
                'model:blogPost' => []
            ]
        ]
    ],
    SiteGroupComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'unPublished' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/unPublish/{id:integer}',
            'defaults' => [
                'controller' => 'unPublished'
            ]
        ]
    ]
];