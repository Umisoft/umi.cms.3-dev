<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\site\theme\view;

use umi\acl\IAclFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [
    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'forumTheme',
    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\ThemeWidget',
        'list' => __NAMESPACE__ . '\widget\ListWidget'
    ],
    SitePageComponent::OPTION_VIEW => [
        'directories' => ['module/forum/conference/view']
    ],
    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => []
            ]
        ]
    ]
];
