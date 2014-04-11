<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\subject;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\news\site\subject\Component',
    
    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
        'subject' => __NAMESPACE__ . '\controller\SubjectController',
        'rss' => __NAMESPACE__ . '\controller\NewsSubjectRssController'
    ],

    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\SubjectWidget',
        'newsList' => __NAMESPACE__ . '\widget\SubjectNewsListWidget',
        'list' => __NAMESPACE__ . '\widget\SubjectListWidget',
        'rss' => __NAMESPACE__ . '\widget\SubjectNewsRssUrlWidget'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'subjectViewer' => [],
            'subjectRssViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:index',
            'controller:subject',
            'controller:rss',
            'widget:view',
            'widget:list',
            'widget:newsList',
            'widget:rss'
        ],
        IAclFactory::OPTION_RULES => [
            'subjectViewer' => [
                'controller:index' => [],
                'controller:subject' => [],
                'widget:view' => [],
                'widget:list' => [],
                'widget:newsList' => []
            ],
            'subjectRssViewer' => [
                'controller:rss' => [],
                'widget:rss' => []
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],

    SiteComponent::OPTION_ROUTES      => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ],
        'subject' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{slug}',
            'defaults' => [
                'controller' => 'subject'
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