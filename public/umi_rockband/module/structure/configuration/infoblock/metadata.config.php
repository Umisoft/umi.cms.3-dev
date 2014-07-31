<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use project\module\structure\model\object\Slider;
use umi\orm\metadata\field\IField;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'infoblock'
        ],
        'fields'     => [
            Slider::FIELD_SLIDE_NAME_1  => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'slider_1',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'slider_1'
                    ],
                    'en-US' => [
                        'columnName' => 'slider_1_en'
                    ]
                ]
            ],
            Slider::FIELD_SLIDE_NAME_2  => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'slider_2',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'slider_2'
                    ],
                    'en-US' => [
                        'columnName' => 'slider_2_en'
                    ]
                ]
            ],
            Slider::FIELD_SLIDE_NAME_3  => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'slider_3',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'slider_3'
                    ],
                    'en-US' => [
                        'columnName' => 'slider_3_en'
                    ]
                ]
            ],
            Slider::FIELD_SLIDE_CONTENT_1  => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'content_1',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'content_1'
                    ],
                    'en-US' => [
                        'columnName' => 'content_1_en'
                    ]
                ]
            ],
            Slider::FIELD_SLIDE_CONTENT_2  => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'content_2',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'content_2'
                    ],
                    'en-US' => [
                        'columnName' => 'content_2_en'
                    ]
                ]
            ],
            Slider::FIELD_SLIDE_CONTENT_3  => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'content_3',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'content_3'
                    ],
                    'en-US' => [
                        'columnName' => 'content_3_en'
                    ]
                ]
            ]
        ],
        'types'      => [
            Slider::TYPE   => [
                'objectClass' => 'project\module\structure\model\object\Slider',
                'fields'      => [
                    Slider::FIELD_SLIDE_NAME_1 => [],
                    Slider::FIELD_SLIDE_NAME_2 => [],
                    Slider::FIELD_SLIDE_NAME_3 => [],
                    Slider::FIELD_SLIDE_CONTENT_1 => [],
                    Slider::FIELD_SLIDE_CONTENT_2 => [],
                    Slider::FIELD_SLIDE_CONTENT_3 => [],
                ]
            ]
        ]
    ]
);
