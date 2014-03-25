<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\SecureAdminComponent;

return [

    SecureAdminComponent::OPTION_CLASS => 'umicms\project\admin\component\SecureAdminComponent',

    SecureAdminComponent::OPTION_COMPONENTS => [
        'page' => '{#lazy:~/project/module/structure/admin/page/component.config.php}',
        'layout' => '{#lazy:~/project/module/structure/admin/layout/component.config.php}',
    ],

    SecureAdminComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'pageEditor' => [],
            'layoutEditor' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:page',
            'component:layout'
        ],
        IAclFactory::OPTION_RULES => [
            'pageEditor' => ['component:page' => []],
            'layoutEditor' => ['component:layout' => []],
        ]
    ],

    SecureAdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];