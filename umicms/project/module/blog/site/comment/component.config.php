<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\CmsHierarchicPageComponent;

return [

    CmsHierarchicPageComponent::OPTION_CLASS => 'umicms\project\site\component\CmsHierarchicPageComponent',
    CmsHierarchicPageComponent::OPTION_COLLECTION_NAME => 'blogComment',
    CmsHierarchicPageComponent::OPTION_CONTROLLERS => [
        'add' => __NAMESPACE__ . '\controller\AddController',
        'publish' => __NAMESPACE__ . '\controller\PublishController',
        'reject' => __NAMESPACE__ . '\controller\RejectController',
    ],
    CmsHierarchicPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\CommentWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'add' => __NAMESPACE__ . '\widget\AddWidget',
        'publish' => __NAMESPACE__ . '\widget\PublishWidget',
        'reject' => __NAMESPACE__ . '\widget\RejectWidget'
    ],
    CmsHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/comment'],
    ],
    CmsHierarchicPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'poster' => ['viewer'],
            'posterPremoderation' => ['viewer'],
            'moderator' => ['poster']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogComment',
            'collection:blogComment'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => []
            ],
            'poster' => [
                'widget:add' => [],
                'controller:add' => [],
                'model:blogComment' => []
            ],
            'posterPremoderation' => [
                'widget:add' => [],
                'controller:add' => [],
                'model:blogComment' => [
                    'publish' => ['premoderation']
                ]
            ],
            'moderator' => [
                'widget:reject' => [],
                'widget:publish' => [],
                'controller:reject' => [],
                'controller:publish' => [],
                'collection:blogComment' => [
                    'getComments' => ['withNeedModeration']
                ],
                'model:blogComment' => []
            ]
        ]
    ],
    CmsHierarchicPageComponent::OPTION_ROUTES => [
        'add' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/add/{parent:integer}',
            'defaults' => [
                'controller' => 'add',
                'parent' => null
            ]
        ],
        'publish' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/publish/{id:integer}',
            'defaults' => [
                'controller' => 'publish'
            ]
        ],
        'reject' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/reject/{id:integer}',
            'defaults' => [
                'controller' => 'reject'
            ]
        ]
    ]
];