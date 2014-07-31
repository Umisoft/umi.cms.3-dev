<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;

return [
    'fields' => [
        'imageMain' => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'image_main'
        ],
        'imageList' => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'image_list'
        ],
        'popular'              => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'popular',
            'defaultValue' => 0
        ],
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
                'imageMain' => [],
                'imageList' => [],
                'firstImage' => [],
                'secondImage' => [],
                'file' => [],
                'secondContents' => [],
                'simpleText' => [],
                'popular' => [],
            ]
        ]
    ]
];