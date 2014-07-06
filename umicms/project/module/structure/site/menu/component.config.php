<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\site\menu;

use umi\acl\IAclFactory;
use umicms\hmvc\component\site\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',

    SiteComponent::OPTION_WIDGET => [
        'auto' => __NAMESPACE__ . '\widget\AutoMenuWidget',
        'custom' => __NAMESPACE__ . '\widget\CustomMenuWidget'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:auto' => [],
                'widget:custom' => [],
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW        => [
        'directories' => ['module/structure/menu']
    ],
];