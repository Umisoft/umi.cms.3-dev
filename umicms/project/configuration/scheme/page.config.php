<?php

use Doctrine\DBAL\Types\Type;
use umicms\project\Environment;

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/scheme/simple.config.php',
    require Environment::$directoryCmsProject . '/configuration/scheme/common.page.config.php',
    require Environment::$directoryCmsProject . '/configuration/scheme/active.config.php',
    require Environment::$directoryCmsProject . '/configuration/scheme/recoverable.config.php',
    [
        'columns' => [
            'slug'             => [
                'type' => Type::STRING
            ],
        ],
        'indexes' => [
            'slug' => [
                'type' => 'unique',
                'columns' => ['slug']
            ]
        ]
    ]
);