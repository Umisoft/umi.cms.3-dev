<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\site;

use umicms\hmvc\component\site\SiteGroupComponent;

return [
    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'dispatch' => '{#lazy:~/project/module/dispatch/site/dispatch/component.config.php}',
        'subscriber' => '{#lazy:~/project/module/dispatch/site/subscriber/component.config.php}',
        //'release' => '{#lazy:~/project/module/dispatch/site/release/component.config.php}',
    ],

    SiteGroupComponent::OPTION_VIEW        => [
        'directories' => ['module/dispatch']
    ]
];