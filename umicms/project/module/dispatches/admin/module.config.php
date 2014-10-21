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
        'dispatch' => '{#lazy:~/project/module/dispatches/admin/dispatch/component.config.php}',
        'subscriber' => '{#lazy:~/project/module/dispatches/admin/subscriber/component.config.php}',
        'release' => '{#lazy:~/project/module/dispatches/admin/release/component.config.php}',
        'template' => '{#lazy:~/project/module/dispatches/admin/template/component.config.php}',
        'subscription' => '{#lazy:~/project/module/dispatches/admin/subscription/component.config.php}',
        'unsubscription' => '{#lazy:~/project/module/dispatches/admin/unsubscription/component.config.php}',
        'releasestatus' => '{#lazy:~/project/module/dispatches/admin/releasestatus/component.config.php}'
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];