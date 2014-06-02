<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\route\IRouteFactory;
use umicms\project\admin\settings\component\DefaultSettingsComponent;

return [

    DefaultSettingsComponent::OPTION_CLASS => 'umicms\project\admin\settings\component\SettingsComponent',

    DefaultSettingsComponent::OPTION_COMPONENTS => [
        'slugify' => '{#lazy:~/project/site/settings/slugify/component.config.php}'
    ],

    DefaultSettingsComponent::OPTION_SETTINGS_CONFIG_ALIAS => '~/project/site/site.settings.config.php',

    DefaultSettingsComponent::OPTION_FORMS => [
        'settings' => '{#lazy:~/project/site/settings/form/settings.php}'
    ],

    DefaultSettingsComponent::OPTION_ROUTES => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]

];
