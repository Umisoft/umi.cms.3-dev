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

use umi\route\IRouteFactory;
use umicms\hmvc\component\admin\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\AdminComponent',

    AdminComponent::OPTION_COMPONENTS => [
        'rubric' => '{#lazy:~/project/module/news/admin/rubric/component.config.php}',
        'item' => '{#lazy:~/project/module/news/admin/item/component.config.php}',
        'subject' => '{#lazy:~/project/module/news/admin/subject/component.config.php}',
        'itemsubject' => '{#lazy:~/project/module/news/admin/itemsubject/component.config.php}',
        'rsssubject' => '{#lazy:~/project/module/news/admin/rsssubject/component.config.php}'
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];