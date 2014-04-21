<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\comment;

use umi\acl\IAclFactory;
use umicms\project\site\component\DefaultSiteHierarchicPageComponent;

return [

    DefaultSiteHierarchicPageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSiteHierarchicPageComponent',
    DefaultSiteHierarchicPageComponent::OPTION_COLLECTION_NAME => 'blogComment',
    DefaultSiteHierarchicPageComponent::OPTION_CONTROLLERS => [
    ],
    DefaultSiteHierarchicPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\BlogCommentWidget',
        'list' => __NAMESPACE__ . '\widget\BlogCommentListWidget'
    ],
    DefaultSiteHierarchicPageComponent::OPTION_VIEW => [
        'type' => 'php',
        'extension' => 'phtml',
        'directories' => __DIR__ . '/template/php',
    ],
    DefaultSiteHierarchicPageComponent::OPTION_ACL => [
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
    ]
];