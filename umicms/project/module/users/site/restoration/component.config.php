<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\restoration;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_COMPONENTS => [
        'confirmation' => '{#lazy:~/project/module/users/site/restoration/confirmation/component.config.php}'
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
        'form' => __NAMESPACE__ . '\widget\FormWidget',
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/users/restoration']
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'index' => 'controller:index',
            'link'  => 'widget:link',
            'form'  => 'widget:form',
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => [],
                'widget:link' => [],
                'widget:form' => []
            ]
        ]
    ],

    SiteComponent::OPTION_ROUTES      => [

        'component' => [
            'type' => 'SiteComponentRoute'
        ],

        'index' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];