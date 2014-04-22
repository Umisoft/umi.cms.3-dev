<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\draft;

use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\BlogDraftPageController',
        'all' => __NAMESPACE__ . '\controller\BlogDraftListController',
        'edit' => __NAMESPACE__ . '\controller\BlogEditDraftController',
        'publish' => __NAMESPACE__ . '\controller\BlogPublishDraftController'
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogDraftWidget',
        'list' => __NAMESPACE__ . '\widget\BlogDraftListWidget',
        'ownList' => __NAMESPACE__ . '\widget\BlogOwnDraftListWidget',
        'ownListUrl' => __NAMESPACE__ . '\widget\BlogOwnDraftListUrlWidget',
        'editDraft' => __NAMESPACE__ . '\widget\BlogEditDraftWidget',
        'publishDraft' => __NAMESPACE__ . '\widget\BlogPublishDraftWidget',
        'editDraftLink' => __NAMESPACE__ . '\widget\BlogEditDraftUrlWidget',
        'allListUrl' => __NAMESPACE__ . '\widget\BlogDraftListUrlWidget'
    ],
    DefaultSitePageComponent::OPTION_VIEW => [
        'type' => 'php',
        'extension' => 'phtml',
        'directories' => [
            __DIR__ . '/template/php',
            CMS_LIBRARY_DIR . '/../project/site/template/php/common'
        ]
    ],
    DefaultSitePageComponent::OPTION_ACL => [
    ],
    DefaultSitePageComponent::OPTION_ROUTES => [
        'edit' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/edit/{id:integer}',
            'defaults' => [
                'controller' => 'edit'
            ]
        ],
        'publish' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route' => '/publish',
            'defaults' => [
                'controller' => 'publish'
            ]
        ],
        'all' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'route' => '/all',
            'defaults' => [
                'controller' => 'all'
            ]
        ]
    ]
];