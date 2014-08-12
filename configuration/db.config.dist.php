<?php

use umi\dbal\toolbox\DbalTools;

return [
    [
        'id'     => 'master',
        'type'   => 'master',
        'connection' => [
            'type' => DbalTools::CONNECTION_TYPE_PDOMYSQL,
            'options' => [
                'dbname' => '%dbname%',
                'user' => '%user%',
                'password' => '%password%',
                'host' => '%host%',
                'charset' => 'utf8'
            ]
        ]
    ]
];