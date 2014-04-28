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
        'draft' => '{#lazy:~/project/module/blog/site/draft/component.config.php}',
        'moderate' => '{#lazy:~/project/module/blog/site/moderate/component.config.php}',
        'category' => '{#lazy:~/project/module/blog/site/category/component.config.php}',
        'author' => '{#lazy:~/project/module/blog/site/author/component.config.php}',
        'tag' => '{#lazy:~/project/module/blog/site/tag/component.config.php}',
        'comment' => '{#lazy:~/project/module/blog/site/comment/component.config.php}'
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\project\site\controller\DefaultStructurePageController'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'author' => ['viewer'],
            'moderator' => ['author']
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:index',
            'component:post',
            'component:draft',
            'component:category',
            'component:author',
            'component:tag',
            'component:comment'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => [],
                'component:post' => [],
                'component:category' => [],
                'component:author' => [],
                'component:tag' => [],
                'component:comment' => []
            ],
            'author' => [
                'component:post' => [
                    'edit' => ['own'],
                    'publish' => ['own', 'unpublished'],
                    'draft' => ['own', 'published']
                ],
                'component:draft' => [
                    'edit' => ['own'],
                    'publish' => ['own', 'unpublished'],
                ],
            ],
            'moderator' => [
                'component:post' => [
                    'edit' => [],
                    'publish' => ['unpublished'],
                    'draft' => ['published']
                ],
                'component:draft' => [
                    'edit' => [],
                    'publish' => ['unpublished'],
                ],
            ]
        ]
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/blog'],
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