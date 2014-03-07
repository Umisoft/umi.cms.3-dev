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
use umicms\api\toolbox\ApiTools;
use umicms\Bootstrap;

return [

    Bootstrap::OPTION_TOOLS => [
        require(FRAMEWORK_LIBRARY_DIR . '/i18n/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/dbal/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/orm/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/authentication/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/stemming/toolbox/config.php'),
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

        ApiTools::NAME => [
            'api' => [
               'umicms\project\module\search\api\SearchIndexApi' => [
                   'collectionsMap' => [
                       'newsItem' => ['properties' => ['displayName', 'announcement']],
                       'newsSubject' => ['properties' => ['displayName', 'h1', 'contents']],
                       'newsRubric' => ['properties' => ['displayName', 'h1', 'contents']],
                       'blogCategory' => ['properties' => ['displayName', 'h1', 'contents']],
                       'blogComment' => ['properties' => ['contents']],
                       'blogPost' => ['properties' => ['displayName', 'h1', 'announcement', 'contents']],
                   ]
               ]
            ]
        ],

        OrmTools::NAME => [
            'factories' => [
                'object' => [
                    'defaultObjectClass' => 'umicms\orm\object\CmsObject',
                    'defaultHierarchicObjectClass' => 'umicms\orm\object\CmsHierarchicObject'
                ],
                'objectCollection' => [
                    'defaultSimpleCollectionClass' => 'umicms\orm\collection\SimpleCollection',
                    'defaultHierarchicCollectionClass' => 'umicms\orm\collection\SimpleHierarchicCollection',
                    'defaultLinkedHierarchicCollectionClass' => 'umicms\orm\collection\LinkedHierarchicCollection',
                    'defaultCommonHierarchyClass' => 'umicms\orm\collection\CommonHierarchy'
                ]
            ],
            'metadata'    => [
                'structure' => '{#lazy:~/project/module/structure/metadata/structure.config.php}',
                'layout' => '{#lazy:~/project/module/structure/metadata/layout.config.php}',

                'newsRubric' => '{#lazy:~/project/module/news/metadata/rubric.config.php}',
                'newsItem' => '{#lazy:~/project/module/news/metadata/news_item.config.php}',
                'newsItemSubject' => '{#lazy:~/project/module/news/metadata/news_item_subject.config.php}',
                'newsSubject' => '{#lazy:~/project/module/news/metadata/subject.config.php}',

                'blogCategory' => '{#lazy:~/project/module/blog/metadata/category.config.php}',
                'blogPost' => '{#lazy:~/project/module/blog/metadata/post.config.php}',
                'blogComment' => '{#lazy:~/project/module/blog/metadata/comment.config.php}',
                'blogTag' => '{#lazy:~/project/module/blog/metadata/tag.config.php}',
                'blogPostTag' => '{#lazy:~/project/module/blog/metadata/post_tag.config.php}',

                'user' => '{#lazy:~/project/module/users/metadata/user.config.php}',
                'searchIndex' => '{#lazy:~/project/module/search/metadata/search_index.config.php}',
            ],

            'collections' => [
                'structure'     => [
                    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
                    'handlers' => [
                        'admin' => 'structure.page',
                        'site' => 'structure'
                    ]
                ],
                'layout'     => [
                    'type' => ICollectionFactory::TYPE_SIMPLE,
                    'handlers' => [
                        'admin' => 'structure',
                        'site' => 'structure'
                    ]
                ],

                'newsRubric' => [
                    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
                    'handlers' => [
                        'admin' => 'news.rubric',
                        'site' => 'news.rubric'
                    ]
                ],
                'newsItem' => [
                    'type' => ICollectionFactory::TYPE_SIMPLE,
                    'handlers' => [
                        'admin' => 'news.item',
                        'site' => 'news.item'
                    ]
                ],
                'newsItemSubject' => [
                    'type' => ICollectionFactory::TYPE_SIMPLE
                ],
                'newsSubject' => [
                    'type' => ICollectionFactory::TYPE_SIMPLE,
                    'handlers' => [
                        'admin' => 'news.subject',
                        'site' => 'news.subject'
                    ]
                ],

                'blogCategory' => [
                    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC
                ],
                'blogPost' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'blogComment' => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC],
                'blogTag' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'blogPostTag' => ['type' => ICollectionFactory::TYPE_SIMPLE],

                'user' => ['type' => ICollectionFactory::TYPE_SIMPLE],
                'searchIndex' => ['type' => ICollectionFactory::TYPE_SIMPLE],
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
