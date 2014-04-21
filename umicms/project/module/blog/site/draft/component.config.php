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
        'editDraft' => __NAMESPACE__ . '\controller\BlogEditDraftController'
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogDraftWidget',
        'list' => __NAMESPACE__ . '\widget\BlogDraftListWidget',
        'editDraft' => __NAMESPACE__ . '\widget\BlogEditDraftWidget',
        'editDraftLink' => __NAMESPACE__ . '\widget\BlogEditDraftUrlWidget',
        'editListDraftUrl' => __NAMESPACE__ . '\widget\BlogDraftListUrlWidget'
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
        'editDraft' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/editDraft/{id:integer}',
            'defaults' => [
                'controller' => 'editDraft'
            ]
        ]
    ]
];