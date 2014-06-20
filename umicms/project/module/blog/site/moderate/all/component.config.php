<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\all;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteComponent;

return [
    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',
    SiteComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\hmvc\component\site\SiteStructurePageController'
    ],
    SiteComponent::OPTION_WIDGET => [
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'listLink' => __NAMESPACE__ . '\widget\ListLinkWidget'
    ],
    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'moderator' => []
        ],
        IAclFactory::OPTION_RULES => [
            'moderator' => [
                'controller:index' => [],
                'widget:list' => [],
                'widget:listLink' => []
            ]
        ]
    ],
    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/blog/moderate/all']
    ],
    SiteComponent::OPTION_ROUTES => [
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];