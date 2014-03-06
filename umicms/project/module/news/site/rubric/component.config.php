<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric;

use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',
    
    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
        'rubric' => __NAMESPACE__ . '\controller\RubricController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ .  '\widget\RubricWidget',
        'newsList' => __NAMESPACE__ . '\widget\RubricNewsListWidget',
        'list' => __NAMESPACE__ .  '\widget\RubricListWidget',
    ],

    SiteComponent::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],

    SiteComponent::OPTION_ROUTES      => [
        'rubric' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route'    => '/(?P<url>.+)',
            'defaults' => [
                'controller' => 'rubric'
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