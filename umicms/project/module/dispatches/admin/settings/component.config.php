<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\hmvc\component\admin\settings\SettingsGroupComponent;

return [

    SettingsGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\settings\SettingsGroupComponent',

    SettingsGroupComponent::OPTION_COMPONENTS => [
        'dispatch' => '{#lazy:~/project/module/dispatches/admin/settings/dispatch/component.config.php}',
    ]

];
