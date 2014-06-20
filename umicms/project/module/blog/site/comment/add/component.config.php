<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment\add;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteComponent;

return [
    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',
    SiteComponent::OPTION_CONTROLLERS => [
        'add' => __NAMESPACE__ . '\controller\AddController'
    ],
    SiteComponent::OPTION_WIDGET => [
        'addForm' => __NAMESPACE__ . '\widget\AddFormWidget'
    ],
    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/blog/comment/add']
    ],
    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'poster' => [],
            'posterPremoderation' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogComment'
        ],
        IAclFactory::OPTION_RULES => [
            'poster' => [
                'widget:addForm' => [],
                'controller:add' => [],
                'model:blogComment' => []
            ],
            'posterPremoderation' => [
                'widget:addForm' => [],
                'controller:add' => [],
                'model:blogComment' => [
                    'publish' => ['premoderation']
                ]
            ]
        ]
    ],
    SiteComponent::OPTION_ROUTES => [
        'add' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{parent:integer}',
            'defaults' => [
                'controller' => 'add',
                'parent' => null
            ]
        ]
    ]
];