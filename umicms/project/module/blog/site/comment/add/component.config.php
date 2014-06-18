<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment\add;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteHierarchicPageComponent;

return [
    SiteHierarchicPageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteHierarchicPageComponent',
    SiteHierarchicPageComponent::OPTION_COLLECTION_NAME => 'blogComment',
    SiteHierarchicPageComponent::OPTION_CONTROLLERS => [
        'add' => __NAMESPACE__ . '\controller\AddController',
    ],
    SiteHierarchicPageComponent::OPTION_WIDGET => [
        'addForm' => __NAMESPACE__ . '\widget\AddWidget',
    ],
    SiteHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/comment/add'],
    ],
    SiteHierarchicPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'poster' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogComment',
        ],
        IAclFactory::OPTION_RULES => [
            'poster' => [
                'widget:addForm' => [],
                'controller:add' => [],
                'model:blogComment' => []
            ],
        ]
    ],
    SiteHierarchicPageComponent::OPTION_ROUTES => [
        'add' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{parent:integer}',
            'defaults' => [
                'controller' => 'add',
                'parent' => null
            ]
        ]
    ]
];