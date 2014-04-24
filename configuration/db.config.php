<?php

use umi\dbal\toolbox\DbalTools;

return [
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
];