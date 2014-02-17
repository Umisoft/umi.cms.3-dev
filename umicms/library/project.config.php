<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\configuration;

use umi\hmvc\component\IComponent;
use umi\orm\collection\ICollectionFactory;
use umi\orm\toolbox\ORMTools;
use umi\route\IRouteFactory;
use umicms\Bootstrap;

return [

    Bootstrap::OPTION_PROJECT_API => [
        'umicms\module\structure\api\StructureApi' => 'umicms\module\structure\api\StructureApi'
    ],

    Bootstrap::OPTION_TOOLS => [
        require(FRAMEWORK_TOOLKIT_DIR . '/i18n/toolbox/config.php'),
        require(FRAMEWORK_TOOLKIT_DIR . '/dbal/toolbox/config.php'),
        require(FRAMEWORK_TOOLKIT_DIR . '/orm/toolbox/config.php'),
    ],

    Bootstrap::OPTION_TOOLS_SETTINGS => [
        ORMTools::NAME => [
            'metadata'    => [
                'structure' => '{#lazy:~/project/module/structure/metadata/structure.config.php}'
            ],

            'collections' => [
                'structure'     => ['type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC]
            ]
        ]
    ],

    IComponent::OPTION_COMPONENTS  => [
        'site'       => '{#lazy:~/project/site/site.config.php}',
        'admin'      => '{#lazy:~/project/admin/admin.config.php}'
    ],

    IComponent::OPTION_CONTROLLERS => [
       'install' =>   'umicms\controller\InstallController'
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