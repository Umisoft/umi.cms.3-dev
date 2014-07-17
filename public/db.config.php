<?php

use umi\dbal\toolbox\DbalTools;

return [
    [
        'id'     => 'master',
        'type'   => 'master',
        'connection' => [
            'type' => DbalTools::CONNECTION_TYPE_PDOMYSQL,
            'options' => [
                'dbname' => 'srv09realloc3',
                'user' => 'srv09realloc3',
                'password' => 'srv09realloc3',
                'host' => 'localhost',
                'charset' => 'utf8'
            ]
        ]
    ]
];