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
                    'defaultAdapter' => [
                        'type' => IAuthenticationFactory::ADAPTER_ORM,
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
                'structure' => '{#lazy:~/project/module/structure/metadata/structure.config.php}',

                'news_rubric' => '{#lazy:~/project/module/news/metadata/rubric.config.php}',
                'news_news_item' => '{#lazy:~/project/module/news/metadata/news_item.config.php}',
                'news_news_item_subject' => '{#lazy:~/project/module/news/metadata/news_item_subject.config.php}',
                'news_subject' => '{#lazy:~/project/module/news/metadata/subject.config.php}',

                'blog_category' => '{#lazy:~/project/module/blog/metadata/category.config.php}',
                'blog_post' => '{#lazy:~/project/module/blog/metadata/post.config.php}',
                'blog_comment' => '{#lazy:~/project/module/blog/metadata/comment.config.php}',
                'blog_tag' => '{#lazy:~/project/module/blog/metadata/tag.config.php}',
                'blog_post_tag' => '{#lazy:~/project/module/blog/metadata/post_tag.config.php}',

                'user' => '{#lazy:~/project/module/users/metadata/user.config.php}',
            ],

            'collections' => [
                'structure'     => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],

                'news_rubric' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'news_news_item' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'news_news_item_subject' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'news_subject' => ['type' => ICollectionFactory::TYPE_SIMPLE],

                'blog_category' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'blog_post' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'blog_comment' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'blog_tag' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'blog_post_tag' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'user' => ['type' => ICollectionFactory::TYPE_SIMPLE],
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
            'route' => '/',
            'defaults' => [
                'component' => 'site'
            ]
        ]
    ],
];