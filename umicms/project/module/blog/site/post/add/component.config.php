<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post\add;

use umi\acl\IAclFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogPost',
    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\AddController',
    ],
    SitePageComponent::OPTION_WIDGET => [
        'addLink' => __NAMESPACE__ . '\widget\AddLinkWidget',
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/post/add'],
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'author' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'author' => [
                'controller:page' => [],
                'widget:addLink' => [],
            ]
        ]
    ]
];