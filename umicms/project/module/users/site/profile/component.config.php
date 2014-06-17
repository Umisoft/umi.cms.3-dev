<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\profile;

use umi\acl\IAclFactory;
use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],

    SiteGroupComponent::OPTION_COMPONENTS => [
        'password' => '{#lazy:~/project/module/users/site/profile/password/component.config.php}'
    ],

    SiteGroupComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
        'view' => __NAMESPACE__ . '\widget\ViewWidget',
    ],

    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/users/profile']
    ],

    SiteGroupComponent::OPTION_ACL => [
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:link' => [],
                'widget:view' => []
            ]
        ]
    ]
];