<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\authentication\adapter\ORMAdapter;
use umi\authentication\toolbox\AuthenticationTools;
use umi\extension\twig\TwigTemplateEngine;
use umi\filter\toolbox\FilterTools;
use umi\form\toolbox\FormTools;
use umi\i18n\toolbox\I18nTools;
use umi\orm\metadata\field\IField;
use umi\orm\toolbox\ORMTools;
use umi\pagination\toolbox\PaginationTools;
use umi\templating\toolbox\TemplatingTools;
use umi\validation\toolbox\ValidationTools;
use umicms\filter\HtmlPurifier;
use umicms\filter\Slug;
use umicms\form\element\Captcha;
use umicms\form\element\File;
use umicms\form\element\Image;
use umicms\form\element\PasswordWithConfirmation;
use umicms\form\element\Wysiwyg;
use umicms\form\element\Permissions;
use umicms\module\toolbox\ModuleTools;
use umicms\orm\metadata\field\relation\CmsObjectRelationField;
use umicms\orm\metadata\field\relation\CmsPageRelationField;
use umicms\orm\metadata\field\SerializedArrayField;
use umicms\project\Environment;
use umicms\slugify\toolbox\SlugGeneratorTools;
use umicms\templating\engine\xslt\XsltTemplateEngine;
use umicms\validation\Range;

return [
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
                    TwigTemplateEngine::NAME => 'umi\extension\twig\TwigTemplateEngine',
                    XsltTemplateEngine::NAME => 'umicms\templating\engine\xslt\XsltTemplateEngine',
                ],
                'defaultOptions' => [
                    TwigTemplateEngine::NAME => [
                        TwigTemplateEngine::OPTION_ENVIRONMENT => [
                            'cache' => Environment::$cacheTemplateEnabled ? '{#localDir:~/project/cache/templates_c/}' : false
                        ]
                    ]
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
                    Image::TYPE_NAME => 'umicms\form\element\Image',
                    Captcha::TYPE_NAME => 'umicms\form\element\Captcha',
                    PasswordWithConfirmation::TYPE_NAME => 'umicms\form\element\PasswordWithConfirmation',
                    Permissions::TYPE_NAME => 'umicms\form\element\Permissions'
                ],
                'elementDefaultOptions' => [
                    Captcha::TYPE_NAME => '{#lazy:~/project/configuration/captcha.config.php}'
                ]
            ]
        ]
    ],

    ModuleTools::NAME => [
        'modules' => '{#lazy:~/project/module/modules.config.php}'
    ],

    OrmTools::NAME => [
        'factories' => [
            'object' => [
                'defaultObjectClass' => 'umicms\orm\object\CmsObject',
                'defaultHierarchicObjectClass' => 'umicms\orm\object\CmsHierarchicObject'
            ],
            'objectCollection' => [
                'defaultSimpleCollectionClass' => 'umicms\orm\collection\CmsCollection',
                'defaultHierarchicCollectionClass' => 'umicms\orm\collection\CmsHierarchicCollection'
            ],
            'selector' => [
                'selectorClass' => 'umicms\orm\selector\CmsSelector'
            ],
            'metadata' => [
                'fieldTypes' => [
                    IField::TYPE_BELONGS_TO => 'umicms\orm\metadata\field\relation\BelongsToRelationField',
                    CmsObjectRelationField::TYPE => 'umicms\orm\metadata\field\relation\CmsObjectRelationField',
                    CmsPageRelationField::TYPE => 'umicms\orm\metadata\field\relation\CmsPageRelationField',
                    SerializedArrayField::TYPE => 'umicms\orm\metadata\field\SerializedArrayField'
                ]
            ]
        ],
        'metadata'    => [
            'structure' => '{#lazy:~/project/module/structure/configuration/structure/metadata.config.php}',
            'layout' => '{#lazy:~/project/module/structure/configuration/layout/metadata.config.php}',
            'infoblock' => '{#lazy:~/project/module/structure/configuration/infoblock/metadata.config.php}',
            'menu' => '{#lazy:~/project/module/structure/configuration/menu/metadata.config.php}',
            'robots' => '{#lazy:~/project/module/structure/configuration/robots/metadata.config.php}',

            'newsRubric' => '{#lazy:~/project/module/news/configuration/rubric/metadata.config.php}',
            'newsItem' => '{#lazy:~/project/module/news/configuration/item/metadata.config.php}',
            'newsRssImportScenario' => '{#lazy:~/project/module/news/configuration/rss/metadata.config.php}',
            'newsItemSubject' => '{#lazy:~/project/module/news/configuration/itemsubject/metadata.config.php}',
            'rssScenarioSubject' => '{#lazy:~/project/module/news/configuration/rsssubject/metadata.config.php}',
            'newsSubject' => '{#lazy:~/project/module/news/configuration/subject/metadata.config.php}',

            'blogCategory' => '{#lazy:~/project/module/blog/configuration/category/metadata.config.php}',
            'blogPost' => '{#lazy:~/project/module/blog/configuration/post/metadata.config.php}',
            'blogPostStatus' => '{#lazy:~/project/module/blog/configuration/poststatus/metadata.config.php}',
            'blogAuthor' => '{#lazy:~/project/module/blog/configuration/author/metadata.config.php}',
            'blogComment' => '{#lazy:~/project/module/blog/configuration/comment/metadata.config.php}',
            'blogCommentStatus' => '{#lazy:~/project/module/blog/configuration/commentstatus/metadata.config.php}',
            'blogTag' => '{#lazy:~/project/module/blog/configuration/tag/metadata.config.php}',
            'blogPostTag' => '{#lazy:~/project/module/blog/configuration/posttag/metadata.config.php}',
            'blogRssImportScenario' => '{#lazy:~/project/module/blog/configuration/rss/metadata.config.php}',
            'rssBlogTag' => '{#lazy:~/project/module/blog/configuration/rsstag/metadata.config.php}',

            'user' => '{#lazy:~/project/module/users/configuration/user/metadata.config.php}',
            'userGroup' => '{#lazy:~/project/module/users/configuration/group/metadata.config.php}',
            'userUserGroup' => '{#lazy:~/project/module/users/configuration/usergroup/metadata.config.php}',

            'searchIndex' => '{#lazy:~/project/module/search/configuration/index/metadata.config.php}',

            'serviceBackup' => '{#lazy:~/project/module/service/configuration/backup/metadata.config.php}'
        ],

        'collections' => [
            'structure'     => '{#lazy:~/project/module/structure/configuration/structure/collection.config.php}',
            'layout'     => '{#lazy:~/project/module/structure/configuration/layout/collection.config.php}',
            'infoblock'     => '{#lazy:~/project/module/structure/configuration/infoblock/collection.config.php}',
            'menu'     => '{#lazy:~/project/module/structure/configuration/menu/collection.config.php}',
            'robots' => '{#lazy:~/project/module/structure/configuration/robots/collection.config.php}',

            'newsRubric' => '{#lazy:~/project/module/news/configuration/rubric/collection.config.php}',
            'newsItem' => '{#lazy:~/project/module/news/configuration/item/collection.config.php}',
            'newsRssImportScenario' => '{#lazy:~/project/module/news/configuration/rss/collection.config.php}',
            'newsItemSubject' => '{#lazy:~/project/module/news/configuration/itemsubject/collection.config.php}',
            'rssScenarioSubject' => '{#lazy:~/project/module/news/configuration/rsssubject/collection.config.php}',
            'newsSubject' => '{#lazy:~/project/module/news/configuration/subject/collection.config.php}',

            'blogCategory' => '{#lazy:~/project/module/blog/configuration/category/collection.config.php}',
            'blogPost' => '{#lazy:~/project/module/blog/configuration/post/collection.config.php}',
            'blogPostStatus' => '{#lazy:~/project/module/blog/configuration/poststatus/collection.config.php}',
            'blogAuthor' => '{#lazy:~/project/module/blog/configuration/author/collection.config.php}',
            'blogComment' => '{#lazy:~/project/module/blog/configuration/comment/collection.config.php}',
            'blogCommentStatus' => '{#lazy:~/project/module/blog/configuration/commentstatus/collection.config.php}',
            'blogTag' => '{#lazy:~/project/module/blog/configuration/tag/collection.config.php}',
            'blogPostTag' => '{#lazy:~/project/module/blog/configuration/posttag/collection.config.php}',
            'blogRssImportScenario' => '{#lazy:~/project/module/blog/configuration/rss/collection.config.php}',
            'rssBlogTag' => '{#lazy:~/project/module/blog/configuration/rsstag/collection.config.php}',

            'user' => '{#lazy:~/project/module/users/configuration/user/collection.config.php}',
            'userGroup' => '{#lazy:~/project/module/users/configuration/group/collection.config.php}',
            'userUserGroup' => '{#lazy:~/project/module/users/configuration/usergroup/collection.config.php}',

            'searchIndex' => '{#lazy:~/project/module/search/configuration/index/collection.config.php}',

            'serviceBackup' => '{#lazy:~/project/module/service/configuration/backup/collection.config.php}'
        ]
    ],

    I18nTools::NAME => [
        'localesServiceClass' => 'umicms\i18n\CmsLocalesService',
        'translatorDictionaries' => '{#lazy:~/project/i18n/dictionary.config.php}',
    ],

    FilterTools::NAME => [
        'factories' => [
            'filter' => [
                'types' => [
                    HtmlPurifier::TYPE => 'umicms\filter\HtmlPurifier',
                    Slug::TYPE => 'umicms\filter\Slug'
                ]
            ]
        ]
    ],

    ValidationTools::NAME => [
        'factories' => [
            'validator' => [
                'types' => [
                    Range::NAME => 'umicms\validation\Range',
                ]
            ]
        ]
    ],

    PaginationTools::NAME => [
        'factories' => [
            'paginator' => [
                'paginatorClass' => 'umicms\pagination\CmsPaginator'
            ]
        ]
    ],

    SlugGeneratorTools::NAME => '{#lazy:~/project/configuration/slugGenerator.config.php}'


];