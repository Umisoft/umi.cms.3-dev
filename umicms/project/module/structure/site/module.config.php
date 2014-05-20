<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\site;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_COMPONENTS => [
        'menu' => '{#lazy:~/project/module/structure/site/menu/component.config.php}',
        'infoblock' => '{#lazy:~/project/module/structure/site/infoblock/component.config.php}'
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        'static' => 'umicms\project\site\controller\DefaultStructurePageController',
    ],

    SiteComponent::OPTION_VIEW        => [
        'directories' => ['module/structure']
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:static',
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:static' => []
            ]
        ]
    ],

    SiteComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => 'SiteComponentRoute'
        ],
        'static' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'static'
            ]
        ],
    ]
];