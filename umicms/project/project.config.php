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
use umi\extension\twig\TwigTemplateEngine;
use umi\form\toolbox\FormTools;
use umi\hmvc\component\IComponent;
use umi\i18n\toolbox\I18nTools;
use umi\orm\toolbox\OrmTools;
use umi\route\IRouteFactory;
use umi\templating\toolbox\TemplatingTools;
use umicms\api\toolbox\ApiTools;
use umicms\Bootstrap;
use umicms\form\element\Wysiwyg;

return [

    Bootstrap::OPTION_TOOLS => [
        require(FRAMEWORK_LIBRARY_DIR . '/i18n/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/dbal/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/orm/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/form/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/filter/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/validation/toolbox/config.php'),
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

        TemplatingTools::NAME => [
            'factories' => [
                'engine' => [
                    'engineClasses' => [
                        TwigTemplateEngine::NAME => 'umi\extension\twig\TwigTemplateEngine'
                    ]
                ]
            ]
        ],

        FormTools::NAME => [
            'factories' => [
                'entity' => [
                    'elementTypes' => [
                        Wysiwyg::TYPE_NAME => 'umicms\form\element\Wysiwyg'
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
               ],
                'umicms\project\module\search\api\SearchApi' => [
                    'minimumWordLength' => 3,
                ],
                'umicms\project\module\statistics\api\MetrikaApi' => [
                    'oauthToken' => '4d4d45a7d4dd462ca9f83e4a8f4bd16b',
                    'apiResources'=>'{#lazy:~/project/module/statistics/admin/metrika/api-resources.config.php}'
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
                ],
                'selector' => [
                    'selectorClass' => 'umicms\orm\selector\CmsSelector'
                ]
            ],
            'metadata'    => [
                'structure' => '{#lazy:~/project/module/structure/orm/structure/metadata.config.php}',
                'layout' => '{#lazy:~/project/module/structure/orm/layout/metadata.config.php}',

                'newsRubric' => '{#lazy:~/project/module/news/orm/rubric/metadata.config.php}',
                'newsItem' => '{#lazy:~/project/module/news/orm/item/metadata.config.php}',
                'newsItemSubject' => '{#lazy:~/project/module/news/orm/itemsubject/metadata.config.php}',
                'newsSubject' => '{#lazy:~/project/module/news/orm/subject/metadata.config.php}',

                'blogCategory' => '{#lazy:~/project/module/blog/orm/category/metadata.config.php}',
                'blogPost' => '{#lazy:~/project/module/blog/orm/post/metadata.config.php}',
                'blogComment' => '{#lazy:~/project/module/blog/orm/comment/metadata.config.php}',
                'blogTag' => '{#lazy:~/project/module/blog/orm/tag/metadata.config.php}',
                'blogPostTag' => '{#lazy:~/project/module/blog/orm/posttag/metadata.config.php}',

                'user' => '{#lazy:~/project/module/users/orm/user/metadata.config.php}',
                'userGroup' => '{#lazy:~/project/module/users/orm/group/metadata.config.php}',
                'userUserGroup' => '{#lazy:~/project/module/users/orm/usergroup/metadata.config.php}',

                'searchIndex' => '{#lazy:~/project/module/search/orm/index/metadata.config.php}',
            ],

            'collections' => [
                'structure'     => '{#lazy:~/project/module/structure/orm/structure/collection.config.php}',
                'layout'     => '{#lazy:~/project/module/structure/orm/layout/collection.config.php}',

                'newsRubric' => '{#lazy:~/project/module/news/orm/rubric/collection.config.php}',
                'newsItem' => '{#lazy:~/project/module/news/orm/item/collection.config.php}',
                'newsItemSubject' => '{#lazy:~/project/module/news/orm/itemsubject/collection.config.php}',
                'newsSubject' => '{#lazy:~/project/module/news/orm/subject/collection.config.php}',

                'blogCategory' => '{#lazy:~/project/module/blog/orm/category/collection.config.php}',
                'blogPost' => '{#lazy:~/project/module/blog/orm/post/collection.config.php}',
                'blogComment' => '{#lazy:~/project/module/blog/orm/comment/collection.config.php}',
                'blogTag' => '{#lazy:~/project/module/blog/orm/tag/collection.config.php}',
                'blogPostTag' => '{#lazy:~/project/module/blog/orm/posttag/collection.config.php}',

                'user' => '{#lazy:~/project/module/users/orm/user/collection.config.php}',
                'userGroup' => '{#lazy:~/project/module/users/orm/group/collection.config.php}',
                'userUserGroup' => '{#lazy:~/project/module/users/orm/usergroup/collection.config.php}',

                'searchIndex' => '{#lazy:~/project/module/search/orm/index/collection.config.php}',
            ]
        ],

        I18nTools::NAME => [
            'translatorDictionaries' => '{#lazy:~/project/i18n/dictionary.config.php}',
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
