<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\post;

use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\blog\site\tag\BlogTagComponent',
    SiteComponent::OPTION_CONTROLLERS => [
    ],
    SiteComponent::OPTION_WIDGET => [
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
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/rss',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'tag' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{slug}',
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