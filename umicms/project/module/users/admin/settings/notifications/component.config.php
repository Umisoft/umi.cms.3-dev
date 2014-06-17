<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\hmvc\component\admin\settings\SettingsComponent;

return [

    SettingsComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\settings\SettingsComponent',

    SettingsComponent::OPTION_SETTINGS_CONFIG_ALIAS => '~/project/module/users/configuration/module.config.php',

    SettingsComponent::OPTION_FORMS => [
        'settings' => '{#lazy:~/project/module/users/admin/settings/notifications/form/settings.php}'
    ],

];
