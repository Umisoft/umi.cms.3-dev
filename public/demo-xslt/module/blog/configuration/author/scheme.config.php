<?php

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;

return [
    'columns' => [
        'avatar' => [
            'type' => Type::STRING
        ],
        'second_contents'         => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
        ],
        'second_contents_en'         => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
        ],
        'simple_text'         => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
        ],
        'first_image' => [
            'type'    => Type::STRING
        ],
        'second_image' => [
            'type'    => Type::STRING
        ],
        'file' => [
            'type'    => Type::STRING
        ],
    ]
];
