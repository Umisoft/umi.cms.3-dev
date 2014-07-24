<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\admin;

use umi\route\IRouteFactory;
use umicms\hmvc\component\admin\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\AdminComponent',

    AdminComponent::OPTION_COMPONENTS => [
        'dispatches' => '{#lazy:~/project/module/dispatches/admin/dispatches/component.config.php}',
        'subscribers' => '{#lazy:~/project/module/dispatches/admin/subscribers/component.config.php}',
        'release' => '{#lazy:~/project/module/dispatches/admin/release/component.config.php}',
        'reason' => '{#lazy:~/project/module/dispatches/admin/reason/component.config.php}',
        'templatemail' => '{#lazy:~/project/module/dispatches/admin/templatemail/component.config.php}',
        'subscribersdispatches' => '{#lazy:~/project/module/dispatches/admin/subscribersdispatches/component.config.php}',
        'unsubscribedispatches' => '{#lazy:~/project/module/dispatches/admin/unsubscribe/component.config.php}'
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];