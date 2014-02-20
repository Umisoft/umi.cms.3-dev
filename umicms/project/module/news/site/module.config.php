<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site;

use umi\hmvc\component\IComponent;
use umi\route\IRouteFactory;

return [
    IComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\project\module\news\site\controller\IndexController',
        'rubric' => 'umicms\project\module\news\site\controller\RubricController',
        'newsItem' => 'umicms\project\module\news\site\controller\NewsItemController',
        'subject' => 'umicms\project\module\news\site\controller\SubjectController'
    ],
    IComponent::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],

    IComponent::OPTION_WIDGET => [
        'viewNewsItem' => 'umicms\project\module\news\site\widget\NewsItemWidget',
        'viewRubric' => 'umicms\project\module\news\site\widget\RubricWidget',
        'viewSubject' => 'umicms\project\module\news\site\widget\SubjectWidget',

        'rubricNewsList' => 'umicms\project\module\news\site\widget\RubricNewsListWidget',
        'subjectNewsList' => 'umicms\project\module\news\site\widget\SubjectNewsListWidget',

        'rubricList' => 'umicms\project\module\news\site\widget\RubricListWidget',
        'subjectList' => 'umicms\project\module\news\site\widget\SubjectListWidget'
    ],

    IComponent::OPTION_ROUTES      => [
        'rubric' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/rubric/{url}',
            'defaults' => [
                'controller' => 'rubric'
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
        ],
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];