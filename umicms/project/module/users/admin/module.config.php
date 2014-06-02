<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
