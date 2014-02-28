<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\subject;

use umi\route\IRouteFactory;
use umicms\hmvc\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\AdminComponent',

    AdminComponent::OPTION_SETTINGS => [
    ],

    AdminComponent::OPTION_CONTROLLERS => [
        'list' => __NAMESPACE__ . '\controller\ListController',
        'item' => __NAMESPACE__ . '\controller\ItemController'
    ],

    AdminComponent::OPTION_ROUTES      => [

        'item' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{collection}/{id:integer}',
            'defaults' => [
                'collection' => 'newsItem',
                'controller' => 'item'
            ]
        ],

        'list' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{collection}',
            'defaults' => [
                'collection' => 'newsItem',
                'controller' => 'list'
            ]
        ]
    ]
];