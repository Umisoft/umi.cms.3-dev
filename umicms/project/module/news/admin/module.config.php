<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\admin;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\project\admin\component\AdminComponent',

    AdminComponent::OPTION_COMPONENTS => [
        'rubric' => '{#lazy:~/project/module/news/admin/rubric/component.config.php}',
        'item' => '{#lazy:~/project/module/news/admin/item/component.config.php}',
        'subject' => '{#lazy:~/project/module/news/admin/subject/component.config.php}',
        'rss' => '{#lazy:~/project/module/news/admin/rss/component.config.php}'
    ],

    AdminComponent::OPTION_ACL => [

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

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];