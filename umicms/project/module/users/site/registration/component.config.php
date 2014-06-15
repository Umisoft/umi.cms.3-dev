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
use umi\route\IRouteFactory;
use umicms\project\module\users\model\object\AuthorizedUser;
use umicms\hmvc\component\site\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',

    SiteComponent::OPTION_COMPONENTS => [
        'activation' => '{#lazy:~/project/module/users/site/registration/activation/component.config.php}'
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
        'form' => __NAMESPACE__ . '\widget\FormWidget',
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/users/registration']
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
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
            'route' => '/{type:string}',
            'defaults' => [
                'type' => AuthorizedUser::TYPE_NAME,
                'controller' => 'index'
            ]
        ]
    ]
];