<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project;

use umi\acl\IAclFactory;
use umi\hmvc\component\IComponent;
use umi\route\IRouteFactory;

return [

    Bootstrap::OPTION_TOOLS => '{#partial:~/project/configuration/tools.config.php}',

    Bootstrap::OPTION_TOOLS_SETTINGS => '{#partial:~/project/configuration/tools.settings.config.php}',

    IComponent::OPTION_COMPONENTS  => [
        'site'       => '{#lazy:~/project/site/configuration/component.config.php}',
        'admin'      => '{#lazy:~/project/admin/configuration/component.config.php}'
    ],

    IComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'adminExecutor' => [],
            'siteExecutor' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:admin',
            'component:site',
        ],
        IAclFactory::OPTION_RULES => [
            'adminExecutor' => ['component:admin' => []],
            'siteExecutor' => ['component:site' => []]
        ]
    ],

    IComponent::OPTION_ROUTES => [

        'admin' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/admin',
            'defaults' => [
                'component' => 'admin'
            ]
        ],

        'site' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'component' => 'site'
            ]
        ]
    ],
];
