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
use umicms\project\admin\component\SecureAdminComponent;

return [

    SecureAdminComponent::OPTION_CLASS => 'umicms\project\admin\component\SecureAdminComponent',

    SecureAdminComponent::OPTION_SETTINGS => [],

    SecureAdminComponent::OPTION_COMPONENTS => [
        'user' => '{#lazy:~/project/module/users/admin/user/component.config.php}'
    ],

    SecureAdminComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'userEditor' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:user'
        ],
        IAclFactory::OPTION_RULES => [
            'userEditor' => ['component:user' => []]
        ]
    ],

    SecureAdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ],
    ]
];
