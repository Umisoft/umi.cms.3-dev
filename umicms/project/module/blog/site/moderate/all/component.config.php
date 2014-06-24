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
use umicms\hmvc\component\site\SitePageComponent;

return [
    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    SitePageComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\hmvc\component\site\SiteStructurePageController',
        'page' => __NAMESPACE__ . '\controller\PageController'
    ],
    SitePageComponent::OPTION_WIDGET => [
        'post' => __NAMESPACE__ . '\widget\PostWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget',
        'listLink' => __NAMESPACE__ . '\widget\LinkWidget'
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'moderator' => []
        ],
        IAclFactory::OPTION_RULES => [
            'moderator' => [
                'controller:index' => [],
                'widget:post' => [],
                'widget:list' => [],
                'widget:listLink' => []
            ]
        ]
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/moderate/all']
    ]
];