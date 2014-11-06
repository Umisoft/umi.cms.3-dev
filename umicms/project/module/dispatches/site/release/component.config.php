<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\release;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
        'imagesrc' => __NAMESPACE__ . '\controller\ImageSrcController',
    ],

    SiteComponent::OPTION_WIDGET => [
        'link' => __NAMESPACE__ . '\widget\LinkWidget',
        'imageSrcLink' => __NAMESPACE__ . '\widget\ImageSrcLinkWidget',
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => [],
                'controller:imagesrc' => [],
                'widget:link' => [],
                'widget:imageSrcLink' => [],
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/dispatch/release']
    ],

    SiteComponent::OPTION_ROUTES      => [
        'index' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{id:integer}/{token:guid}',
            'defaults' => [
                'controller' => 'index'
            ]
        ],
        'imagesrc' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/{release:guid}/{token:guid}',
            'defaults' => [
                'controller' => 'imagesrc'
            ]
        ]
    ]

];