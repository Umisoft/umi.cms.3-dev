<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\settings\component\SettingsComponent;

return [

    SettingsComponent::OPTION_CLASS => 'umicms\project\admin\settings\component\SettingsComponent',

    SettingsComponent::OPTION_COMPONENTS => [
        'backup' => '{#lazy:~/project/module/service/settings/backup/component.config.php}'
    ],
    SettingsComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'configurator' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:backup'
        ],
        IAclFactory::OPTION_RULES => [
            'configurator' => ['component:backup' => []]
        ]
    ],

    SettingsComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];