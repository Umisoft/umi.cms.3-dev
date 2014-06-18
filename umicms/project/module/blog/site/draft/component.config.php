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
use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',
    SiteGroupComponent::OPTION_CONTROLLERS => [
        'publish' => __NAMESPACE__ . '\controller\PublishController',
        'sendToModeration' => __NAMESPACE__ . '\controller\SendToModerationController',
    ],
    SiteGroupComponent::OPTION_COMPONENTS => [
        'edit' => '{#lazy:~/project/module/blog/site/draft/edit/component.config.php}',
        'view' => '{#lazy:~/project/module/blog/site/draft/view/component.config.php}'
    ],
    SiteGroupComponent::OPTION_WIDGET => [
        'publishDraft' => __NAMESPACE__ . '\widget\PublishWidget',
        'sendToModeration' => __NAMESPACE__ . '\widget\SendToModerationWidget'
    ],
    SiteGroupComponent::OPTION_ACL => [
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
                'controller:sendToModeration' => [],
                'widget:sendToModeration' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'publisher' => [
                'controller:publish' => [],
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
    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/blog/draft'],
    ],
    SiteGroupComponent::OPTION_ROUTES => [
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