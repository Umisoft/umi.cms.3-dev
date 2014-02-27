<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project;

use umi\authentication\adapter\ORMAdapter;
use umi\authentication\IAuthenticationFactory;
use umi\authentication\toolbox\AuthenticationTools;
use umi\hmvc\component\IComponent;
use umi\orm\collection\ICollectionFactory;
use umi\orm\toolbox\OrmTools;
use umi\route\IRouteFactory;
use umicms\Bootstrap;

return [

    Bootstrap::OPTION_TOOLS => [
        require(FRAMEWORK_LIBRARY_DIR . '/i18n/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/dbal/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/orm/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/authentication/toolbox/config.php'),
        require(CMS_LIBRARY_DIR . '/api/toolbox/config.php'),
        require(CMS_LIBRARY_DIR . '/serialization/toolbox/config.php')
    ],

    Bootstrap::OPTION_TOOLS_SETTINGS => [

        AuthenticationTools::NAME => [
            'factories' => [
                'authentication' => [
                    'adapterClasses' => [
                        'ormUserAdapter' => 'umicms\authentication\OrmUserAdapter'
                    ],
                    'defaultAdapter' => [
                        'type' => 'ormUserAdapter',
                        'options' => [
                            ORMAdapter::OPTION_COLLECTION => 'user',
                            ORMAdapter::OPTION_LOGIN_FIELDS => ['login', 'email'],
                            ORMAdapter::OPTION_PASSWORD_FIELD => 'password'
                        ]
                    ],
                    'defaultStorage' => [
                        'type' => IAuthenticationFactory::STORAGE_ORM_SESSION
                    ]
                ]
             ]
        ],

        OrmTools::NAME => [
            'factories' => [
                'object' => [
                    'defaultObjectClass' => 'umicms\base\object\CmsObject',
                    'defaultHierarchicObjectClass' => 'umicms\base\object\CmsElement'
                ]
            ],
            'metadata'    => [
                'Structure' => '{#lazy:~/project/module/structure/metadata/structure.config.php}',
                'Layout' => '{#lazy:~/project/module/structure/metadata/layout.config.php}',

                'NewsRubric' => '{#lazy:~/project/module/news/metadata/rubric.config.php}',
                'NewsItem' => '{#lazy:~/project/module/news/metadata/news_item.config.php}',
                'NewsItemSubject' => '{#lazy:~/project/module/news/metadata/news_item_subject.config.php}',
                'NewsSubject' => '{#lazy:~/project/module/news/metadata/subject.config.php}',

                'BlogCategory' => '{#lazy:~/project/module/blog/metadata/category.config.php}',
                'BlogPost' => '{#lazy:~/project/module/blog/metadata/post.config.php}',
                'BlogComment' => '{#lazy:~/project/module/blog/metadata/comment.config.php}',
                'BlogTag' => '{#lazy:~/project/module/blog/metadata/tag.config.php}',
                'BlogPostTag' => '{#lazy:~/project/module/blog/metadata/post_tag.config.php}',

                'User' => '{#lazy:~/project/module/users/metadata/user.config.php}',
            ],

            'collections' => [
                'Structure'     => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'Layout'     => ['type' => ICollectionFactory::TYPE_SIMPLE],

                'NewsRubric' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'NewsItem' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'NewsItemSubject' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'NewsSubject' => ['type' => ICollectionFactory::TYPE_SIMPLE],

                'BlogCategory' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'BlogPost' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'BlogComment' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'BlogTag' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'BlogPostTag' => ['type' => ICollectionFactory::TYPE_SIMPLE],

                'User' => ['type' => ICollectionFactory::TYPE_SIMPLE],
            ]
        ]
    ],

    IComponent::OPTION_COMPONENTS  => [
        'site'       => '{#lazy:~/project/site/site.config.php}',
        'admin'      => '{#lazy:~/project/admin/admin.config.php}'
    ],

    IComponent::OPTION_ROUTES => [

        'admin' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/admin',
            'defaults' => [
                'component' => 'admin'
            ]
        ],

        'site' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'component' => 'site'
            ]
        ]
    ],
];