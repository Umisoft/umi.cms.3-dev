<?php

use Doctrine\DBAL\Types\Type;

return [
    'columns' => [
        'active'          => [
            'type'    => Type::BOOLEAN,
            'options' => [
                'default' => 1
            ]
        ]
    ],
    'indexes' => [
        'active' => [
            'columns' => ['active']
        ]
    ]
];