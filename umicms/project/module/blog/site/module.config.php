<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_COMPONENTS => [
        'post' => '{#lazy:~/project/module/blog/site/post/component.config.php}',
        'category' => '{#lazy:~/project/module/blog/site/category/component.config.php}',
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        //'index' => 'umicms\project\module\blog\site\controller\IndexController'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'newsViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:index'
        ],
        IAclFactory::OPTION_RULES => [
            'newsViewer' => [
                'controller:index' => []
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php',
    ],

    SiteComponent::OPTION_ROUTES      => [

        'component' => [
            'type' => 'SiteComponentRoute'
        ],
        'index' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'index'
            ]
        ]
    ]
];