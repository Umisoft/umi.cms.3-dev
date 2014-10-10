<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace umicms\project\site\settings\mail;

use umicms\hmvc\component\admin\settings\SettingsComponent;

return [

    SettingsComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\settings\SettingsComponent',

    SettingsComponent::OPTION_CONTROLLERS => [
        SettingsComponent::INTERFACE_LAYOUT_CONTROLLER => __NAMESPACE__  . '\controller\LayoutController',
        SettingsComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController'
    ],

    SettingsComponent::OPTION_SETTINGS_CONFIG_ALIAS => '~/project/configuration/messagesTools.config.php',

    SettingsComponent::OPTION_FORMS => [
        'settings' => '{#lazy:~/project/site/settings/mail/form/settings.php}'
    ],

];
