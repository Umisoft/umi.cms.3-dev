<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\SecureAdminComponent;

return [

    SecureAdminComponent::OPTION_CLASS => 'umicms\project\admin\component\SecureAdminComponent',

    SecureAdminComponent::OPTION_COMPONENTS => [
        'rubric' => '{#lazy:~/project/module/news/admin/rubric/component.config.php}',
        'item' => '{#lazy:~/project/module/news/admin/item/component.config.php}',
        'subject' => '{#lazy:~/project/module/news/admin/subject/component.config.php}',
        'rss' => '{#lazy:~/project/module/news/admin/rss/component.config.php}'
    ],

    SecureAdminComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'rubricEditor' => [],
            'itemEditor' => [],
            'subjectEditor' => [],
            'rssEditor' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:rubric',
            'component:item',
            'component:subject',
            'component:rss'
        ],
        IAclFactory::OPTION_RULES => [
            'rubricEditor' => ['component:rubric' => []],
            'itemEditor' => ['component:item' => []],
            'subjectEditor' => ['component:subject' => []],
            'rssEditor' => ['component:rss' => []]
        ]
    ],

    SecureAdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];