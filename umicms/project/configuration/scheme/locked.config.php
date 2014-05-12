<?php

use Doctrine\DBAL\Types\Type;

return [
    'columns' => [
        'locked'          => [
            'type'    => Type::BOOLEAN,
            'options' => [
                'default' => 0
            ]
        ]
    ]
];