<?php

use umi\dbal\toolbox\DbalTools;

return [
    [
        'id'     => 'master',
        'type'   => 'master',
        'connection' => [
            'type' => DbalTools::CONNECTION_TYPE_PDOMYSQL,
            'options' => [
                'dbname' => 'umi',
                'user' => 'root',
                'password' => 'root',
                'host' => 'localhost',
                'charset' => 'utf8',
                'port' => 8889
            ]
        ]
    ]
];