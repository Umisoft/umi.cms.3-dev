<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\view;

use umi\acl\IAclFactory;
use umi\acl\IAclManager;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [
    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PageController',
        'all' => __NAMESPACE__ . '\controller\ListController'
    ],
    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\PostWidget',
        'ownList' => __NAMESPACE__ . '\widget\OwnListWidget',
        'ownListLink' => __NAMESPACE__ . '\widget\OwnListLinkWidget',
        'allList' => __NAMESPACE__ . '\widget\AllListWidget',
        'allListLink' => __NAMESPACE__ . '\widget\AllListLinkWidget',
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'moderator' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:page' => [],
                'widget:view' => [],
                'widget:ownList' => [],
                'widget:ownListLink' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'moderator' => [
                'controller:page' => [],
                'controller:all' => [],
                'widget:view' => [],
                'widget:allList' => [],
                'widget:allListLink' => [],
                'model:blogPost' => []
            ]
        ]
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/moderate/view'],
    ],
    SitePageComponent::OPTION_ROUTES => [
        'all' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/all',
            'defaults' => [
                'controller' => 'all'
            ]
        ]
    ]
];