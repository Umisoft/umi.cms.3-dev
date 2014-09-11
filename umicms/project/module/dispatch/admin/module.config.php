<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\admin;

use umi\route\IRouteFactory;
use umicms\hmvc\component\admin\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\AdminComponent',

    AdminComponent::OPTION_COMPONENTS => [
        'dispatch' => '{#lazy:~/project/module/dispatch/admin/dispatch/component.config.php}',
        'subscriber' => '{#lazy:~/project/module/dispatch/admin/subscriber/component.config.php}',
        'release' => '{#lazy:~/project/module/dispatch/admin/release/component.config.php}',
        'reason' => '{#lazy:~/project/module/dispatch/admin/reason/component.config.php}',
        'templatemail' => '{#lazy:~/project/module/dispatch/admin/templatemail/component.config.php}',
        'subscribersdispatches' => '{#lazy:~/project/module/dispatch/admin/subscribersdispatches/component.config.php}',
        'unsubscribedispatches' => '{#lazy:~/project/module/dispatch/admin/unsubscribedispatches/component.config.php}',
        'usergroupdispatches' => '{#lazy:~/project/module/dispatch/admin/usergroupdispatches/component.config.php}'
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];