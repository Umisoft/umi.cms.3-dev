<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\serialization\ISerializerFactory;

return [

    ApiApplication::OPTION_CLASS => 'umicms\project\admin\api\ApiApplication',

    ApiApplication::OPTION_SERIALIZERS => [
        ISerializerFactory::TYPE_JSON => [
            'umi\orm\collection\BaseCollection' => 'umicms\serialization\json\orm\CollectionSerializer',
            'umi\orm\metadata\Metadata' => 'umicms\serialization\json\orm\MetadataSerializer',
            'umi\orm\metadata\ObjectType' => 'umicms\serialization\json\orm\ObjectTypeSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\json\orm\FieldSerializer',
            'umicms\orm\object\CmsObject' => 'umicms\serialization\json\orm\CmsAdminObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\json\orm\CmsAdminObjectSerializer',
            'umi\orm\selector\Selector' => 'umicms\serialization\json\orm\SelectorSerializer'
        ]
    ],

    ApiApplication::OPTION_CONTROLLERS => [
        ApiApplication::ERROR_CONTROLLER   => 'umicms\project\admin\controller\ErrorController',

        'settings' => __NAMESPACE__ . '\controller\ApiSettingsController',
        'action' => __NAMESPACE__ . '\controller\ApiActionController'
    ],

    ApiApplication::OPTION_COMPONENTS => [

        'blog' => '{#lazy:~/project/module/blog/admin/module.config.php}',
        'service' => '{#lazy:~/project/module/service/admin/module.config.php}',
        'files' => '{#lazy:~/project/module/files/admin/module.config.php}',
        'models' => '{#lazy:~/project/module/models/admin/module.config.php}',
        'news' => '{#lazy:~/project/module/news/admin/module.config.php}',
        'seo' => '{#lazy:~/project/module/seo/admin/module.config.php}',
        'search' => '{#lazy:~/project/module/search/admin/module.config.php}',
        'statistics' => '{#lazy:~/project/module/statistics/admin/module.config.php}',
        'structure' => '{#lazy:~/project/module/structure/admin/module.config.php}',
        'users' => '{#lazy:~/project/module/users/admin/module.config.php}',
        'testmodule' => '{#lazy:~/project/module/testmodule/admin/module.config.php}'
    ],

    ApiApplication::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [

            'administrator' => [],

            'blogEditor' => ['administrator'],
            'serviceEditor' => ['administrator'],
            'filesEditor' => ['administrator'],
            'modelsEditor' => ['administrator'],
            'newsEditor' => ['administrator'],
            'seoEditor' => ['administrator'],
            'searchEditor' => ['administrator'],
            'statisticsEditor' => ['administrator'],
            'structureEditor' => ['administrator'],
            'usersEditor' => ['administrator'],
        ],
        IAclFactory::OPTION_RESOURCES => [

            'controller:settings',

            'component:blog',
            'component:service',
            'component:files',
            'component:models',
            'component:news',
            'component:seo',
            'component:search',
            'component:statistics',
            'component:structure',
            'component:users'
        ],
        IAclFactory::OPTION_RULES => [

            'administrator' => ['controller:settings' => []],

            'blogEditor' => ['component:blog' => []],
            'serviceEditor' => ['component:service' => []],
            'filesEditor' => ['component:files' => []],
            'modelsEditor' => ['component:models' => []],
            'newsEditor' => ['component:news' => []],
            'seoEditor' => ['component:seo' => []],
            'searchEditor' => ['component:search' => []],
            'statisticsEditor' => ['component:statistics' => []],
            'structureEditor' => ['component:structure' => []],
            'usersEditor' => ['component:users' => []],
        ]
    ],

    ApiApplication::OPTION_ROUTES => [

        'action' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => 'action',
                'ignoreCsrf' => true
            ]
        ],

        'index' => [
            'type'     => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'settings'
            ],
            'subroutes' => [
                'component' => [
                    'type'     => IRouteFactory::ROUTE_SIMPLE,
                    'route' => '/{component}'
                ]
            ]
        ],


    ]

];