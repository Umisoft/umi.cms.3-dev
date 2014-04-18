<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project;

use umi\acl\IAclFactory;
use umi\authentication\adapter\ORMAdapter;
use umi\authentication\toolbox\AuthenticationTools;
use umi\extension\twig\TwigTemplateEngine;
use umi\form\toolbox\FormTools;
use umi\hmvc\component\IComponent;
use umi\i18n\toolbox\I18nTools;
use umi\orm\metadata\field\IField;
use umi\orm\toolbox\OrmTools;
use umi\route\IRouteFactory;
use umi\templating\toolbox\TemplatingTools;
use umicms\Bootstrap;
use umicms\form\element\File;
use umicms\form\element\Image;
use umicms\form\element\Wysiwyg;
use umicms\module\toolbox\ModuleTools;

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
        require(FRAMEWORK_LIBRARY_DIR . '/acl/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/rss/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/pagination/toolbox/config.php'),
        require(CMS_LIBRARY_DIR . '/module/toolbox/config.php'),
        require(CMS_LIBRARY_DIR . '/model/toolbox/config.php'),
        require(CMS_LIBRARY_DIR . '/serialization/toolbox/config.php')
    ],

    Bootstrap::OPTION_TOOLS_SETTINGS => [

        AuthenticationTools::NAME => [
            'factories' => [
                'authentication' => [
                    'adapterClasses' => [
                        'cmsUserAdapter' => 'umicms\authentication\CmsUserAdapter'
                    ],
                    'storageClasses' => [
                        'cmsAuthStorage' => 'umicms\authentication\CmsAuthStorage'
                    ],
                    'defaultAdapter' => [
                        'type' => 'cmsUserAdapter',
                        'options' => [
                            ORMAdapter::OPTION_COLLECTION => 'user',
                            ORMAdapter::OPTION_LOGIN_FIELDS => ['login', 'email'],
                            ORMAdapter::OPTION_PASSWORD_FIELD => 'password'
                        ]
                    ],
                    'defaultStorage' => [
                        'type' => 'cmsAuthStorage'
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
                        Wysiwyg::TYPE_NAME => 'umicms\form\element\Wysiwyg',
                        File::TYPE_NAME => 'umicms\form\element\File',
                        Image::TYPE_NAME => 'umicms\form\element\Image'
                    ]
                ]
            ]
        ],

        ModuleTools::NAME => [
            'modules' => '{#partial:~/project/modules.config.php}'
        ],

        OrmTools::NAME => [
            'factories' => [
                'object' => [
                    'defaultObjectClass' => 'umicms\orm\object\CmsObject',
                    'defaultHierarchicObjectClass' => 'umicms\orm\object\CmsHierarchicObject'
                ],
                'objectCollection' => [
                    'defaultSimpleCollectionClass' => 'umicms\orm\collection\SimpleCollection',
                    'defaultHierarchicCollectionClass' => 'umicms\orm\collection\SimpleHierarchicCollection'
                ],
                'selector' => [
                    'selectorClass' => 'umicms\orm\selector\CmsSelector'
                ],
                'metadata' => [
                    'fieldTypes' => [
                        IField::TYPE_BELONGS_TO => 'umicms\orm\metadata\field\relation\BelongsToRelationField'
                    ]
                ]
            ],
            'metadata'    => [
                'structure' => '{#lazy:~/project/module/structure/configuration/structure/metadata.config.php}',
                'layout' => '{#lazy:~/project/module/structure/configuration/layout/metadata.config.php}',

                'newsRubric' => '{#lazy:~/project/module/news/configuration/rubric/metadata.config.php}',
                'newsItem' => '{#lazy:~/project/module/news/configuration/item/metadata.config.php}',
                'newsRssImportScenario' => '{#lazy:~/project/module/news/configuration/rss/metadata.config.php}',
                'newsItemSubject' => '{#lazy:~/project/module/news/configuration/itemsubject/metadata.config.php}',
                'rssScenarioSubject' => '{#lazy:~/project/module/news/configuration/rsssubject/metadata.config.php}',
                'newsSubject' => '{#lazy:~/project/module/news/configuration/subject/metadata.config.php}',

                'blogCategory' => '{#lazy:~/project/module/blog/configuration/category/metadata.config.php}',
                'blogPost' => '{#lazy:~/project/module/blog/configuration/post/metadata.config.php}',
                'blogAuthor' => '{#lazy:~/project/module/blog/configuration/author/metadata.config.php}',
                'blogComment' => '{#lazy:~/project/module/blog/configuration/comment/metadata.config.php}',
                'blogTag' => '{#lazy:~/project/module/blog/configuration/tag/metadata.config.php}',
                'blogPostTag' => '{#lazy:~/project/module/blog/configuration/posttag/metadata.config.php}',
                'blogRssImportScenario' => '{#lazy:~/project/module/blog/configuration/rss/metadata.config.php}',
                'rssBlogTag' => '{#lazy:~/project/module/blog/configuration/rsstag/metadata.config.php}',

                'user' => '{#lazy:~/project/module/users/configuration/user/metadata.config.php}',
                'userGroup' => '{#lazy:~/project/module/users/configuration/group/metadata.config.php}',
                'userUserGroup' => '{#lazy:~/project/module/users/configuration/usergroup/metadata.config.php}',

                'searchIndex' => '{#lazy:~/project/module/search/configuration/index/metadata.config.php}',

                'serviceBackup' => '{#lazy:~/project/module/service/configuration/backup/metadata.config.php}',

                'testTest' => '{#lazy:~/project/module/testmodule/configuration/test/metadata.config.php}',
            ],

            'collections' => [
                'structure'     => '{#lazy:~/project/module/structure/configuration/structure/collection.config.php}',
                'layout'     => '{#lazy:~/project/module/structure/configuration/layout/collection.config.php}',

                'newsRubric' => '{#lazy:~/project/module/news/configuration/rubric/collection.config.php}',
                'newsItem' => '{#lazy:~/project/module/news/configuration/item/collection.config.php}',
                'newsRssImportScenario' => '{#lazy:~/project/module/news/configuration/rss/collection.config.php}',
                'newsItemSubject' => '{#lazy:~/project/module/news/configuration/itemsubject/collection.config.php}',
                'rssScenarioSubject' => '{#lazy:~/project/module/news/configuration/rsssubject/collection.config.php}',
                'newsSubject' => '{#lazy:~/project/module/news/configuration/subject/collection.config.php}',

                'blogCategory' => '{#lazy:~/project/module/blog/configuration/category/collection.config.php}',
                'blogPost' => '{#lazy:~/project/module/blog/configuration/post/collection.config.php}',
                'blogAuthor' => '{#lazy:~/project/module/blog/configuration/author/collection.config.php}',
                'blogComment' => '{#lazy:~/project/module/blog/configuration/comment/collection.config.php}',
                'blogTag' => '{#lazy:~/project/module/blog/configuration/tag/collection.config.php}',
                'blogPostTag' => '{#lazy:~/project/module/blog/configuration/posttag/collection.config.php}',
                'blogRssImportScenario' => '{#lazy:~/project/module/blog/configuration/rss/collection.config.php}',
                'rssBlogTag' => '{#lazy:~/project/module/blog/configuration/rsstag/collection.config.php}',

                'user' => '{#lazy:~/project/module/users/configuration/user/collection.config.php}',
                'userGroup' => '{#lazy:~/project/module/users/configuration/group/collection.config.php}',
                'userUserGroup' => '{#lazy:~/project/module/users/configuration/usergroup/collection.config.php}',

                'searchIndex' => '{#lazy:~/project/module/search/configuration/index/collection.config.php}',

                'serviceBackup' => '{#lazy:~/project/module/service/configuration/backup/collection.config.php}',

                'testTest' => '{#lazy:~/project/module/testmodule/configuration/test/collection.config.php}',
            ]
        ],

        I18nTools::NAME => [
            'translatorDictionaries' => '{#lazy:~/project/i18n/dictionary.config.php}',
        ]
    ],

    IComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'visitor' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:admin'
        ],
        IAclFactory::OPTION_RULES => [
            'visitor' => ['component:admin' => []]
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
