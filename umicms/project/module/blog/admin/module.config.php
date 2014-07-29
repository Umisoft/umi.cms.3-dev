<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\admin;

use umi\route\IRouteFactory;
use umicms\hmvc\component\admin\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\AdminComponent',

    AdminComponent::OPTION_COMPONENTS => [
        'category' => '{#lazy:~/project/module/blog/admin/category/component.config.php}',
        'post' => '{#lazy:~/project/module/blog/admin/post/component.config.php}',
        'author' => '{#lazy:~/project/module/blog/admin/author/component.config.php}',
        'comment' => '{#lazy:~/project/module/blog/admin/comment/component.config.php}',
        'tag' => '{#lazy:~/project/module/blog/admin/tag/component.config.php}',
        'posttag' => '{#lazy:~/project/module/blog/admin/posttag/component.config.php}',
        'rsstag' => '{#lazy:~/project/module/blog/admin/rsstag/component.config.php}',
        'poststatus' => '{#lazy:~/project/module/blog/admin/poststatus/component.config.php}',
        'commentstatus' => '{#lazy:~/project/module/blog/admin/commentstatus/component.config.php}',
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];