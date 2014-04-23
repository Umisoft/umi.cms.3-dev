<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\route\IRouteFactory;
use umicms\project\admin\settings\component\SettingsComponent;

return [

    SettingsComponent::OPTION_CLASS => 'umicms\project\admin\settings\component\SettingsComponent',

    SettingsComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\project\admin\settings\controller\SettingsController'
    ],

    SettingsComponent::OPTION_FORMS => [
        'settings' => '{#lazy:~/project/module/service/settings/backup/form/settings.php}'
    ],

    SettingsComponent::OPTION_ROUTES => [
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];
