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

use umi\route\IRouteFactory;
use umicms\hmvc\component\admin\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\AdminComponent',

    AdminComponent::OPTION_SETTINGS => [],

    AdminComponent::OPTION_COMPONENTS => [
        'user' => '{#lazy:~/project/module/users/admin/user/component.config.php}',
        'group' => '{#lazy:~/project/module/users/admin/group/component.config.php}',
        'usergroup' => '{#lazy:~/project/module/users/admin/usergroup/component.config.php}',
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ],
    ]
];
