<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\item;

use umi\route\IRouteFactory;
use umicms\base\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\base\component\SiteComponent',
    
    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
        'item' => __NAMESPACE__ . '\controller\NewsItemController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\NewsItemWidget',
        'list' => __NAMESPACE__ . '\widget\NewsItemListWidget'
    ],

    SiteComponent::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],

    SiteComponent::OPTION_ROUTES      => [
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