<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\settings\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\module\settings\admin\component\SettingsComponent;

return [
    SettingsComponent::OPTION_CLASS => 'umicms\project\module\settings\admin\component\SettingsComponent',

    SettingsComponent::OPTION_SKIP_IN_DOCK => true,

    SettingsComponent::OPTION_COMPONENTS => [
        'site' => '{#lazy:~/project/site/settings/component.config.php}',
        'users' => '{#lazy:~/project/module/users/settings/component.config.php}',
        'service' => '{#lazy:~/project/module/service/settings/module.config.php}',
        'seo' => '{#lazy:~/project/module/seo/settings/module.config.php}',
        'statistics' => '{#lazy:~/project/module/statistics/settings/module.config.php}',
        'forms' => '{#lazy:~/project/module/forms/settings/module.config.php}',
    ],

    SettingsComponent::OPTION_CONTROLLERS => [
        'settings' => __NAMESPACE__ . '\controller\SettingsController'
    ],

    SettingsComponent::OPTION_ACL => [

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

    SettingsComponent::OPTION_ROUTES => [
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