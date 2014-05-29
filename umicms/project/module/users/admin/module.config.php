<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_SETTINGS => [],

    AdminComponent::OPTION_COMPONENTS => [
        'user' => '{#lazy:~/project/module/users/admin/user/component.config.php}',
        'group' => '{#lazy:~/project/module/users/admin/group/component.config.php}',
    ],

    AdminComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'userEditor' => [],
            'groupEditor' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:user',
            'component:group',
        ],
        IAclFactory::OPTION_RULES => [
            'userEditor' => ['component:user' => []],
            'groupEditor' => ['component:group' => []],
        ]
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ],
    ]
];
