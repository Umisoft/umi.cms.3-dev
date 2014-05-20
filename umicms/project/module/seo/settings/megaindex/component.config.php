<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\admin\settings\component\DefaultSettingsComponent;

return [

    DefaultSettingsComponent::OPTION_CLASS => 'umicms\project\admin\settings\component\DefaultSettingsComponent',

    DefaultSettingsComponent::OPTION_SETTINGS_CONFIG_ALIAS => '~/project/module/seo/configuration/megaindex/model.settings.config.php',

    DefaultSettingsComponent::OPTION_FORMS => [
        'settings' => '{#lazy:~/project/module/seo/settings/megaindex/form/settings.php}'
    ],

];
