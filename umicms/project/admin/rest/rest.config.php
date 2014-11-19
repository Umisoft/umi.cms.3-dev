<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\rest;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\serialization\ISerializerFactory;

return [

    RestApplication::OPTION_CLASS => 'umicms\project\admin\rest\RestApplication',

    RestApplication::OPTION_SERIALIZERS => [
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

    RestApplication::OPTION_CONTROLLERS => [
        RestApplication::ERROR_CONTROLLER   => 'umicms\project\admin\controller\ErrorController',

        'settings' => __NAMESPACE__ . '\controller\SettingsController',
        RestApplication::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController'
    ],

    RestApplication::OPTION_MODIFY_ACTIONS => [
        'login' => [], 'logout' => [], 'changeLocale' => []
    ],

    RestApplication::OPTION_QUERY_ACTIONS => [
        'form' => [], 'auth' => [], 'locales' => []
    ],

    RestApplication::OPTION_COMPONENTS => [

        'structure' => '{#lazy:~/project/module/structure/admin/module.config.php}',
        'users' => '{#lazy:~/project/module/users/admin/module.config.php}',
        'news' => '{#lazy:~/project/module/news/admin/module.config.php}',
        'blog' => '{#lazy:~/project/module/blog/admin/module.config.php}',
        'seo' => '{#lazy:~/project/module/seo/admin/module.config.php}',
        'surveys' => '{#lazy:~/project/module/surveys/admin/module.config.php}',
        'files' => '{#lazy:~/project/module/files/admin/module.config.php}',
        'service' => '{#lazy:~/project/module/service/admin/module.config.php}',
        'settings' => '{#lazy:~/project/module/settings/admin/module.config.php}'
    ],

    RestApplication::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:action' => [],
                'controller:error' => []
            ]
        ]
    ],

    RestApplication::OPTION_ROUTES => [

        'action' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/action/{action}',
            'defaults' => [
                'controller' => RestApplication::ACTION_CONTROLLER,
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
        ]
    ]

];