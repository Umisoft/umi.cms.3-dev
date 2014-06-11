<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\admin\rest\component\SettingsGroupComponent;

return [

    SettingsGroupComponent::OPTION_CLASS => 'umicms\project\admin\rest\component\SettingsGroupComponent',

    SettingsGroupComponent::OPTION_COMPONENTS => [
        'common' => '{#lazy:~/project/site/settings/common/component.config.php}',
        'seo' => '{#lazy:~/project/site/settings/seo/component.config.php}',
        'templating' => '{#lazy:~/project/site/settings/templating/component.config.php}'
    ]

];
