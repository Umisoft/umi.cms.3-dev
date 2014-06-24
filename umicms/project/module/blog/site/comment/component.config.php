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
use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',
    SiteGroupComponent::OPTION_CONTROLLERS => [
        'publish' => __NAMESPACE__ . '\controller\PublishController',
        'reject' => __NAMESPACE__ . '\controller\RejectController'
    ],
    SiteGroupComponent::OPTION_COMPONENTS => [
        'add' => '{#lazy:~/project/module/blog/site/comment/add/component.config.php}'
    ],
    SiteGroupComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\CommentWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'publishForm' => __NAMESPACE__ . '\widget\PublishFormWidget',
        'rejectForm' => __NAMESPACE__ . '\widget\RejectFormWidget'
    ],
    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/blog/comment']
    ],
    SiteGroupComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'moderator' => []
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
            'moderator' => [
                'widget:rejectForm' => [],
                'widget:publishForm' => [],
                'controller:reject' => [],
                'controller:publish' => [],
                'collection:blogComment' => [
                    'getCommentsWithNeedModeration' => []
                ],
                'model:blogComment' => []
            ]
        ]
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
        ]
    ]
];