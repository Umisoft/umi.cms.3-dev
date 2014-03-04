<?php
namespace demohunt;

use umi\dbal\toolbox\DbalTools;
use umi\hmvc\component\IComponent;
use umi\route\IRouteFactory;
use umicms\Bootstrap;

return [
    Bootstrap::OPTION_TOOLS_SETTINGS => [
        DbalTools::NAME => [
            'servers' => [
                [
                    'id'     => 'master',
                    'type'   => 'master',
                    'connection' => [
                        'type' => DbalTools::CONNECTION_TYPE_PDOMYSQL,
                        'options' => [
                            'dbname' => 'srv09realloc',
                            'user' => 'root',
                            'password' => '',
                            'host' => '127.0.0.1',
                            'charset' => 'utf8'
                        ]
                    ]
                ]
            ]
        ]
    ],

    IComponent::OPTION_CONTROLLERS => [
        'install' =>   'demohunt\controller\InstallController'
    ],

    IComponent::OPTION_ROUTES => [
        'install' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'priority' => 0,
            'route' => '/install',
            'defaults' => [
                'controller' => 'install'
            ]
        ]
    ]

];
