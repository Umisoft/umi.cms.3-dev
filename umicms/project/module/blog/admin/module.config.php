<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_COMPONENTS => [
        'category' => '{#lazy:~/project/module/blog/admin/category/component.config.php}',
        'post' => '{#lazy:~/project/module/blog/admin/post/component.config.php}',
        'author' => '{#lazy:~/project/module/blog/admin/author/component.config.php}',
        'comment' => '{#lazy:~/project/module/blog/admin/comment/component.config.php}',
        'tag' => '{#lazy:~/project/module/blog/admin/tag/component.config.php}'
    ],

    AdminComponent::OPTION_ACL => [
        AdminComponent::OPTION_ACL => [

            IAclFactory::OPTION_ROLES => [
                'categoryEditor' => [],
                'postEditor' => [],
                'tagEditor' => [],
                'rssEditor' => []
            ],
            IAclFactory::OPTION_RESOURCES => [
                'component:category',
                'component:post',
                'component:tag',
                'component:rss'
            ],
            IAclFactory::OPTION_RULES => [
                'categoryEditor' => ['component:category' => []],
                'postEditor' => ['component:post' => []],
                'tagEditor' => ['component:tag' => []],
                'rssEditor' => ['component:rss' => []]
            ]
        ],
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];