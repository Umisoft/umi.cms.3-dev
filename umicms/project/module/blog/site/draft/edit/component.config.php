<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\draft\edit;

use umi\acl\IAclFactory;
use umi\acl\IAclManager;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\IndexController',
    ],
    SitePageComponent::OPTION_WIDGET => [
        'editLink' => __NAMESPACE__ . '\widget\EditUrlWidget',
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
            'publisher' => [],
        ],
        IAclFactory::OPTION_RESOURCES => [
            'model:blogPost'
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:index' => [],
                'controller:page' => [],
                'widget:editDraftLink' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'publisher' => [
                'controller:index' => [],
                'controller:page' => [],
                'controller:publish' => [],
                'widget:view' => [],
                'widget:ownList' => [],
                'widget:ownListLink' => [],
                'widget:editDraftLink' => [],
                'widget:publishDraft' => [],
                'model:blogPost' => [
                    IAclManager::OPERATION_ALL => ['own']
                ]
            ],
            'moderator' => [
                'model:blogPost' => []
            ],
        ]
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/draft/edit'],
    ]
];