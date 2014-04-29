<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\draft;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\BlogDraftPageController',
        'edit' => __NAMESPACE__ . '\controller\BlogEditDraftController',
        'publish' => __NAMESPACE__ . '\controller\BlogPublishDraftController',
        'sendToModeration' => __NAMESPACE__ . '\controller\PostSendToModerationController',
    ],
    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\DraftWidget',
        'ownList' => __NAMESPACE__ . '\widget\DraftOwnListWidget',
        'ownListUrl' => __NAMESPACE__ . '\widget\DraftOwnListUrlWidget',
        'editDraft' => __NAMESPACE__ . '\widget\EditWidget',
        'publishDraft' => __NAMESPACE__ . '\widget\PublishWidget',
        'editDraftLink' => __NAMESPACE__ . '\widget\DraftEditUrlWidget',
        'sendToModeration' => __NAMESPACE__ . '\widget\SendToModerationWidget'
    ],
    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'moderator' => ['author']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:page',
            'controller:edit',
            'controller:publish',
            'controller:sendToModeration',
            'widget:view',
            'widget:ownList',
            'widget:ownListUrl',
            'widget:editDraft',
            'widget:publishDraft',
            'widget:editDraftLink',
            'widget:sendToModeration'
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:edit' => [
                    'edit' => ['own']
                ],
                'controller:publish' => [
                    'publish' => ['own', 'unpublished']
                ],
                'widget:ownList' => [],
                'widget:ownListUrl' => [],
                'widget:editDraft' => [
                    'edit' => ['own']
                ],
                'widget:publishDraft' => [
                    'publish' => ['own', 'unpublished']
                ],
                'widget:editDraftLink' => [
                    'draft' => ['own']
                ],
            ],
            'moderator' => [
                'controller:all' => [],
                'controller:edit' => [
                    'edit' => []
                ],
                'controller:publish' => [
                    'publish' => ['unpublished']
                ],
                'widget:ownList' => [],
                'widget:ownListUrl' => [],
                'widget:editDraft' => [],
                'widget:publishDraft' => [
                    'publish' => ['published']
                ],
                'widget:editDraftLink' => [],
            ]
        ]
    ],
    DefaultSitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/draft'],
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