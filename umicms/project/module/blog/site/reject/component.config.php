<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\reject;

use umi\acl\IAclFactory;
use umi\acl\IAclManager;
use umi\route\IRouteFactory;
use umicms\project\site\component\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\project\site\component\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PostPageController',
        'edit' => __NAMESPACE__ . '\controller\PostEditController',
        'sendToModeration' => __NAMESPACE__ . '\controller\PostSendToModerationController',
    ],
    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\PostWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'listLink' => __NAMESPACE__ . '\widget\ListLinkWidget',
        'editPostLink' => __NAMESPACE__ . '\widget\PostEditLinkWidget',
        'sendToModeration' => __NAMESPACE__ . '\widget\PostSendToModerationWidget'

    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'moderator' => ['author']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:index' => [],
                'controller:page' => [],
                'controller:edit' => [],
                'controller:sendToModeration' => [],
                'widget:view' => [],
                'widget:list' => [],
                'widget:listLink' => [],
                'widget:editPostLink' => [],
                'widget:sendToModeration' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'moderator' => [
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => []
                ]
            ]
        ]
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/reject'],
    ],
    SitePageComponent::OPTION_ROUTES => [
        'edit' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/edit/{id:integer}',
            'defaults' => [
                'controller' => 'edit'
            ]
        ],
        'sendToModeration' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/sendToModeration/{id:integer}',
            'defaults' => [
                'controller' => 'sendToModeration'
            ]
        ]
    ]
];