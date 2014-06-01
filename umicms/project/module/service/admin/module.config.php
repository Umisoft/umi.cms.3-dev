<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',
    AdminComponent::OPTION_COMPONENTS => [
        'backup' => '{#lazy:~/project/module/service/admin/backup/component.config.php}'
    ],
    AdminComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'backupEditor' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:backup'
        ],
        IAclFactory::OPTION_RULES => [
            'backupEditor' => ['component:backup' => []]
        ]
    ],
    AdminComponent::OPTION_ROUTES => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];