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
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\news\site\item\Component',
    
    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
        'item' => __NAMESPACE__ . '\controller\NewsItemController',
        'rss' => __NAMESPACE__ . '\controller\NewsItemRssController'
    ],

    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\NewsItemWidget',
        'list' => __NAMESPACE__ . '\widget\NewsItemListWidget',
        'rss' => __NAMESPACE__ . '\widget\NewsItemListRssUrlWidget'
    ],

    SiteComponent::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'newsItemViewer' => [],
            'newsItemRssViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:index',
            'controller:item',
            'controller:rss',
            'widget:view',
            'widget:list',
            'widget:rss'
        ],
        IAclFactory::OPTION_RULES => [
            'newsItemViewer' => [
                'controller:index' => [],
                'controller:item' => [],
                'widget:view' => [],
                'widget:list' => []
            ],
            'newsItemRssViewer' => [
                'controller:rss' => [],
                'widget:rss' => []
            ]
        ]
    ],

    SiteComponent::OPTION_ROUTES      => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'item' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{slug}',
            'defaults' => [
                'controller' => 'item'
            ]
        ],
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];