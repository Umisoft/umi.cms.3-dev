<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site;

use umicms\hmvc\component\site\SiteGroupComponent;
use umi\acl\IAclFactory;
use umi\route\IRouteFactory;

return [
    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'subscriber' => '{#lazy:~/project/module/dispatches/site/subscriber/component.config.php}',
        'subscription' => '{#lazy:~/project/module/dispatches/site/subscription/component.config.php}',
        'unsubscription' => '{#lazy:~/project/module/dispatches/site/unsubscription/component.config.php}',
    ],

    SiteGroupComponent::OPTION_WIDGET => [
        'list' => __NAMESPACE__ . '\widget\DispatchesListWidget',
        'link' => __NAMESPACE__ . '\widget\ManageSubscriptionLinkWidget',
    ],

    SiteGroupComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:list' => [],
                'widget:link' => [],
            ]
        ]
    ],

    SiteGroupComponent::OPTION_VIEW        => [
        'directories' => ['module/dispatch']
    ],
    SiteGroupComponent::OPTION_ROUTES => [
        'index' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{type:string}/{id:integer}',
            'defaults' => [
                'type' => '',
                'id' => null
            ]
        ]
    ]
];