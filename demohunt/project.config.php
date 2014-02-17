<?php
namespace demohunt;

use umi\dbal\toolbox\DbalTools;
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
                            'user' => 'srv09realloc',
                            'password' => 'srv09realloc',
                            'host' => 'srv01.megaserver.umisoft.ru',
                            'charset' => 'utf8'
                        ]
                    ]
                ]
            ]
        ]
    ]
];