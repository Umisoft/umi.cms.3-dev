<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\unsubscription;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
    ],

    SiteComponent::OPTION_FORMS => [
        'unsubscription' => '{#lazy:~/project/module/dispatches/site/unsubscription/form/unsubscribe.config.php}'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => [],
                'widget:link' => [],
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/dispatch/unsubscription']
    ],

    SiteComponent::OPTION_ROUTES      => [
        'index' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{token:guid}',
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];