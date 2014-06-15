<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\draft;

use umi\acl\IAclFactory;
use umi\acl\IAclManager;
use umi\route\IRouteFactory;
use umicms\project\site\component\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\project\site\component\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\BlogDraftPageController',
        'edit' => __NAMESPACE__ . '\controller\BlogEditDraftController',
        'publish' => __NAMESPACE__ . '\controller\BlogPublishDraftController',
        'sendToModeration' => __NAMESPACE__ . '\controller\PostSendToModerationController',
    ],
    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\DraftWidget',
        'ownList' => __NAMESPACE__ . '\widget\DraftOwnListWidget',
        'ownListLink' => __NAMESPACE__ . '\widget\DraftOwnListLinkWidget',
        'publishDraft' => __NAMESPACE__ . '\widget\PublishWidget',
        'editDraftLink' => __NAMESPACE__ . '\widget\DraftEditUrlWidget',
        'sendToModeration' => __NAMESPACE__ . '\widget\SendToModerationWidget'
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'publisher' => [],
            'moderator' => ['publisher']
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
                'widget:ownList' => [],
                'widget:ownListLink' => [],
                'widget:editDraftLink' => [],
                'widget:sendToModeration' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'publisher' => [
                'controller:index' => [],
                'controller:page' => [],
                'controller:edit' => [],
                'controller:publish' => [],
                'widget:view' => [],
                'widget:ownList' => [],
                'widget:ownListLink' => [],
                'widget:editDraftLink' => [],
                'widget:publishDraft' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'moderator' => [
                'model:blogPost' => []
            ],
        ]
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/draft'],
    ],
    SitePageComponent::OPTION_ROUTES => [
        'edit' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/edit/{id:integer}',
            'defaults' => [
                'controller' => 'edit'
            ]
        ],
        'publish' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/publish/{id:integer}',
            'defaults' => [
                'controller' => 'publish'
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