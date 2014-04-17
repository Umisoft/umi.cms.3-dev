<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\author;

use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\blog\site\author\BlogAuthorComponent',
    SiteComponent::OPTION_CONTROLLERS => [
        'author' => __NAMESPACE__ . '\controller\BlogAuthorController',
        'rss' => __NAMESPACE__ . '\controller\BlogAuthorRssController'
    ],
    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogAuthorWidget',
        'postList' => __NAMESPACE__ . '\widget\BlogAuthorPostListWidget',
        'list' => __NAMESPACE__ . '\widget\BlogAuthorListWidget',
        'rss' => __NAMESPACE__ . '\widget\BlogAuthorListRssUrlWidget'
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
        'author' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{slug}',
            'defaults' => [
                'controller' => 'author'
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