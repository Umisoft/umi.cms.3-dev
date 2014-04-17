<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\comment;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\module\blog\site\comment\BlogCommentComponent',
    SiteComponent::OPTION_CONTROLLERS => [
        'comment' => __NAMESPACE__ . '\controller\BlogCommentController',
    ],
    SiteComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogCommentWidget',
        'list' => __NAMESPACE__ . '\widget\BlogCommentListWidget'
    ],
    SiteComponent::OPTION_VIEW => [
        'type' => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],
    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'blogCommentViewer' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:item',
            'widget:view',
            'widget:list',
        ],
        IAclFactory::OPTION_RULES => [
            'blogPostViewer' => [
                'controller:item' => [],
                'widget:view' => [],
                'widget:list' => []
            ]
        ]
    ],
    SiteComponent::OPTION_ROUTES => [
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];