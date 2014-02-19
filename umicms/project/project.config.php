<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project;

use umi\hmvc\component\IComponent;
use umi\orm\collection\ICollectionFactory;
use umi\orm\toolbox\ORMTools;
use umi\route\IRouteFactory;
use umicms\library\Bootstrap;

return [

    Bootstrap::OPTION_PROJECT_API => [
        'umicms\project\module\structure\api\StructureApi' => 'umicms\project\module\structure\api\StructureApi',
        'umicms\project\module\news\api\CategoryApi' => 'umicms\project\module\news\api\CategoryApi'
    ],

    Bootstrap::OPTION_TOOLS => [
        require(FRAMEWORK_TOOLKIT_DIR . '/i18n/toolbox/config.php'),
        require(FRAMEWORK_TOOLKIT_DIR . '/dbal/toolbox/config.php'),
        require(FRAMEWORK_TOOLKIT_DIR . '/orm/toolbox/config.php'),
    ],

    Bootstrap::OPTION_TOOLS_SETTINGS => [
        ORMTools::NAME => [
            'metadata'    => [
                'structure' => '{#lazy:~/project/module/structure/metadata/structure.config.php}',

                'news_category' => '{#lazy:~/project/module/news/metadata/category.config.php}',
                'news_news_item' => '{#lazy:~/project/module/news/metadata/news_item.config.php}',
                'news_news_item_subject' => '{#lazy:~/project/module/news/metadata/news_item_subject.config.php}',
                'news_subject' => '{#lazy:~/project/module/news/metadata/subject.config.php}',

                'blog_category' => '{#lazy:~/project/module/blog/metadata/category.config.php}',
                'blog_post' => '{#lazy:~/project/module/blog/metadata/post.config.php}',
                'blog_comment' => '{#lazy:~/project/module/blog/metadata/comment.config.php}',
                'blog_tag' => '{#lazy:~/project/module/blog/metadata/tag.config.php}',
                'blog_post_tag' => '{#lazy:~/project/module/blog/metadata/post_tag.config.php}',
            ],

            'collections' => [
                'structure'     => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],

                'news_category' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'news_news_item' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'news_news_item_subject' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'news_subject' => ['type' => ICollectionFactory::TYPE_SIMPLE],

                'blog_category' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'blog_post' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'blog_comment' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'blog_tag' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'blog_post_tag' => ['type' => ICollectionFactory::TYPE_SIMPLE],
            ]
        ]
    ],

    IComponent::OPTION_COMPONENTS  => [
        'site'       => '{#lazy:~/project/site/site.config.php}',
        'admin'      => '{#lazy:~/project/admin/admin.config.php}'
    ],

    IComponent::OPTION_CONTROLLERS => [
       'install' =>   'umicms\project\controller\InstallController'
    ],

    IComponent::OPTION_ROUTES => [

        'install' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/install',
            'defaults' => [
                'controller' => 'install'
            ]
        ],

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