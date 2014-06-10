<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\settings;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;

return [
    SettingsApplication::OPTION_CLASS => 'umicms\project\admin\settings\SettingsApplication',

    SettingsApplication::OPTION_COMPONENTS => [
        'site' => '{#lazy:~/project/site/settings/component.config.php}',
        'users' => '{#lazy:~/project/module/users/settings/component.config.php}',
        'service' => '{#lazy:~/project/module/service/settings/module.config.php}',
        'seo' => '{#lazy:~/project/module/seo/settings/module.config.php}',
        'statistics' => '{#lazy:~/project/module/statistics/settings/module.config.php}',
        'forms' => '{#lazy:~/project/module/forms/settings/module.config.php}',
    ],

    SettingsApplication::OPTION_CONTROLLERS => [
        SettingsApplication::ERROR_CONTROLLER   => 'umicms\project\admin\controller\ErrorController',
        'settings' => __NAMESPACE__ . '\controller\SettingsController'
    ],

    SettingsApplication::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'configurator' => [],
            'serviceExecutor' => ['configurator'],
            'siteExecutor' => ['configurator'],
            'usersExecutor' => ['configurator'],
            'seoExecutor' => ['configurator'],
            'statisticsExecutor' => ['configurator'],
            'formsExecutor' => ['configurator'],
        ],
        IAclFactory::OPTION_RULES => [
            'configurator' => ['controller:settings' => []]
        ]
    ],

    SettingsApplication::OPTION_ROUTES => [
        'index' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'settings'
            ],
            'subroutes' => [
                'component' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route' => '/{component}'
                ]
            ]
        ],
    ]
];