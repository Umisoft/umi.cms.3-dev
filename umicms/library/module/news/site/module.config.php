<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\news\site;

use umi\hmvc\component\IComponent;
use umi\route\IRouteFactory;

return [
    IComponent::OPTION_CONTROLLERS => [
        'category' => 'umicms\module\news\site\controller\CategoryController',
        'newsItem' => 'umicms\module\news\site\controller\NewsItemController',
        'subject' => 'umicms\module\news\site\controller\SubjectController',
    ],
    IComponent::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],
    IComponent::OPTION_ROUTES      => [
        'category' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/category/{slug}',
            'defaults' => [
                'controller' => 'category'
            ]
        ],
        'newsItem' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/item/{slug}',
            'defaults' => [
                'controller' => 'newsItem'
            ]
        ],
        'subject' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/subject/{slug}',
            'defaults' => [
                'controller' => 'subject'
            ]
        ]
    ]
];