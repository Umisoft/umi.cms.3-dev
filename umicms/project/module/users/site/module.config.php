<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_COMPONENTS => [
        'authorization' => '{#lazy:~/project/module/users/site/authorization/component.config.php}',
        'registration' => '{#lazy:~/project/module/users/site/registration/component.config.php}',
        'restoration' => '{#lazy:~/project/module/users/site/restoration/component.config.php}',
        'profile' => '{#lazy:~/project/module/users/site/profile/component.config.php}',
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\project\site\controller\DefaultStructurePageController'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => []
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW        => [
        'directories' => ['module/users']
    ],

    SiteComponent::OPTION_ROUTES      => [

        'component' => [
            'type' => 'SiteComponentRoute'
        ],
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];