<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate;

use umi\acl\IAclFactory;
use umi\acl\IAclManager;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteGroupComponent;

return [
    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',
    SiteGroupComponent::OPTION_CONTROLLERS => [
        'publish' => __NAMESPACE__ . '\controller\PublishController',
        'reject' => __NAMESPACE__ . '\controller\RejectController',
        'draft' => __NAMESPACE__ . '\controller\DraftController'
    ],
    SiteGroupComponent::OPTION_COMPONENTS => [
        'edit' => '{#lazy:~/project/module/blog/site/moderate/edit/component.config.php}',
        'view' => '{#lazy:~/project/module/blog/site/moderate/view/component.config.php}',
        'all' => '{#lazy:~/project/module/blog/site/moderate/all/component.config.php}'
    ],
    SiteGroupComponent::OPTION_WIDGET => [
        'publish' => __NAMESPACE__ . '\widget\PublishWidget',
        'reject' => __NAMESPACE__ . '\widget\RejectWidget',
        'draft' => __NAMESPACE__ . '\widget\DraftWidget'
    ],
    SiteGroupComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'moderator' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:draft' => [],
                'widget:draft' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'moderator' => [
                'controller:publish' => [],
                'controller:reject' => [],
                'controller:draft' => [],
                'widget:publish' => [],
                'widget:reject' => [],
                'widget:draft' => [],
                'model:blogPost' => []
            ]
        ]
    ],
    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/blog/moderate']
    ],
    SiteGroupComponent::OPTION_ROUTES => [
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
        ],
        'draft' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/draft/{id:integer}',
            'defaults' => [
                'controller' => 'draft'
            ]
        ]
    ]
];