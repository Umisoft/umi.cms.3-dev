<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\site\menu;

use umi\acl\IAclFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_WIDGET => [
        'auto' => __NAMESPACE__ . '\widget\AutoMenuWidget'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'widget:auto',
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:auto' => []
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW        => [
        'directories' => ['module/structure/menu']
    ],
];