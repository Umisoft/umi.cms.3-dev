<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\registration;

use umi\acl\IAclFactory;
use umicms\project\module\users\model\object\AuthorizedUser;
use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'activation' => '{#lazy:~/project/module/users/site/registration/activation/component.config.php}'
    ],

    SiteGroupComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],

    SiteGroupComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
        'form' => __NAMESPACE__ . '\widget\FormWidget',
    ],

    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/users/registration']
    ],

    SiteGroupComponent::OPTION_ACL => [
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:link' => [],
                'widget:form' => []
            ]
        ]
    ],

    SiteGroupComponent::OPTION_ROUTES      => [
        'index' => [
            'route' => '/{type:string}',
            'defaults' => [
                'type' => AuthorizedUser::TYPE_NAME
            ]
        ]
    ]
];