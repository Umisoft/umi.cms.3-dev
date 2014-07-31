<?php

use umi\orm\metadata\field\IField;

return [
    'fields' => [
        'firstImage' => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'first_image'
        ],
        'secondImage' => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'second_image'
        ],
        'file' => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'file'
        ],
        'secondContents' => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'second_contents',
            'localizations' => [
                'ru-RU' => ['columnName' => 'second_contents'],
                'en-US' => ['columnName' => 'second_contents_en']
            ]
        ],
        'simpleText' => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'simple_text'
        ],
    ],
    'types' => [
        'base' => [
            'fields' => [
                'firstImage' => [],
                'secondImage' => [],
                'file' => [],
                'secondContents' => [],
                'simpleText' => [],
            ]
        ]
    ]
];