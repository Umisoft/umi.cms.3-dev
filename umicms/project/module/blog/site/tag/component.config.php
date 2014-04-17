<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\tag;

use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\blog\site\tag\BlogTagComponent',
    SiteComponent::OPTION_CONTROLLERS => [
        'tag' => __NAMESPACE__ . '\controller\BlogTagController',
        'rss' => __NAMESPACE__ . '\controller\BlogTagRssController'
    ],
    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogTagWidget',
        'postList' => __NAMESPACE__ . '\widget\BlogTagPostListWidget',
        'list' => __NAMESPACE__ . '\widget\BlogTagListWidget',
        'rss' => __NAMESPACE__ . '\widget\BlogTagListRssUrlWidget'
    ],
    SiteComponent::OPTION_VIEW => [
        'type' => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],
    SiteComponent::OPTION_ACL => [
    ],
    SiteComponent::OPTION_ROUTES => [
        'rss' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'tag' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{slug}',
            'defaults' => [
                'controller' => 'tag'
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