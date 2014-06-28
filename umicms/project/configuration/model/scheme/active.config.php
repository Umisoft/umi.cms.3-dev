<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема колонок для коллекций, поддерживающих управлению активностью объекта на сайте.
 */
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