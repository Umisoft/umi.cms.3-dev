<?php

use Doctrine\DBAL\Types\Type;

return [
    'columns' => [
        'trashed'          => [
            'type'    => Type::BOOLEAN,
            'options' => [
                'default' => 0
            ]
        ]
    ],
    'indexes' => [
        'trashed' => [
            'columns' => ['trashed']
        ]
    ]
];