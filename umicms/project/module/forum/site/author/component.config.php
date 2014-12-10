<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\site\author;

use umicms\hmvc\component\site\SiteGroupComponent;

return [
    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_CONTROLLERS => [
    ],

    SiteGroupComponent::OPTION_COMPONENTS => [
        'view' => '{#lazy:~/project/module/forum/site/author/view/component.config.php}'
    ],

    SiteGroupComponent::OPTION_ACL => [
    ],

    SiteGroupComponent::OPTION_WIDGET => [
    ],

    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/forum/conference']
    ],

    SiteGroupComponent::OPTION_ROUTES => [
    ]
];