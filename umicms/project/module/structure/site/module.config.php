<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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