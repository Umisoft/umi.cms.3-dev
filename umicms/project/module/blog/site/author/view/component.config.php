<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author\view;

use umi\acl\IAclFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [
    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'blogAuthor',
    SitePageComponent::OPTION_CONTROLLERS => [
        'index' => __NAMESPACE__ . '\controller\IndexController',
    ],
    SitePageComponent::OPTION_WIDGET => [
        'author' => __NAMESPACE__ . '\widget\AuthorWidget',
        'list' => __NAMESPACE__ . '\widget\ListAuthorWidget',
        'posts' => __NAMESPACE__ . '\widget\ListPostWidget',
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:author' => [],
                'widget:list' => [],
                'widget:posts' => [],
            ]
        ]
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/blog/author/view'],
    ]
];