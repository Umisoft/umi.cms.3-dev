<?php

use umi\dbal\toolbox\DbalTools;

return [
    [
        'id'     => 'master',
        'type'   => 'master',
        'connection' => [
            'type' => DbalTools::CONNECTION_TYPE_PDOMYSQL,
            'options' => [
                'dbname' => '',
                'user' => '',
                'password' => '',
                'host' => 'localhost',
                'charset' => 'utf8'
            ]
        ]
    ]
];