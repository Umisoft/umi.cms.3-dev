<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',

    SiteComponent::OPTION_COMPONENTS => [
        'post' => '{#lazy:~/project/module/blog/site/post/component.config.php}',
        'draft' => '{#lazy:~/project/module/blog/site/draft/component.config.php}',
        'moderate' => '{#lazy:~/project/module/blog/site/moderate/component.config.php}',
        'reject' => '{#lazy:~/project/module/blog/site/reject/component.config.php}',
        'category' => '{#lazy:~/project/module/blog/site/category/component.config.php}',
        'author' => '{#lazy:~/project/module/blog/site/author/component.config.php}',
        'tag' => '{#lazy:~/project/module/blog/site/tag/component.config.php}',
        'comment' => '{#lazy:~/project/module/blog/site/comment/component.config.php}'
    ],

    SiteComponent::OPTION_CONTROLLERS => [
        'index' => 'umicms\hmvc\component\site\SiteStructurePageController'
    ],

    SiteComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'author' => ['viewer'],
            'moderator' => ['author']
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:index' => []
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