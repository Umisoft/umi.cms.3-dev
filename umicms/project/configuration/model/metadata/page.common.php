<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umicms\filter\Slug;
use umicms\orm\object\ICmsPage;

/**
 * Общие метаданные для всех коллекций, объекты которых имеют страницу на сайте.
 */
return [
    'fields'     => [
        ICmsPage::FIELD_PAGE_SLUG             => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => [],
                Slug::TYPE => []
            ],
        ],
        ICmsPage::FIELD_PAGE_META_TITLE       => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'meta_title',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'meta_title'
                ],
                'en-US' => [
                    'columnName' => 'meta_title_en'
                ]
            ]
        ],
        ICmsPage::FIELD_PAGE_META_DESCRIPTION => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_description',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'meta_description'
                ],
                'en-US' => [
                    'columnName' => 'meta_description_en'
                ]
            ]
        ],
        ICmsPage::FIELD_PAGE_META_KEYWORDS    => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'meta_keywords',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'meta_keywords'
                ],
                'en-US' => [
                    'columnName' => 'meta_keywords_en'
                ]
            ]
        ],
        ICmsPage::FIELD_PAGE_H1 => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'h1',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'h1'
                ],
                'en-US' => [
                    'columnName' => 'h1_en'
                ]
            ]
        ],
        ICmsPage::FIELD_PAGE_CONTENTS         => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'contents',
            'localizations' => [
                'ru-RU' => ['columnName' => 'contents'],
                'en-US' => ['columnName' => 'contents_en']
            ]
        ],
        ICmsPage::FIELD_PAGE_LAYOUT           => [
            'type'       => IField::TYPE_BELONGS_TO,
            'columnName' => 'layout_id',
            'target'     => 'layout'
        ],
    ],
    'types'      => [
        'base' => [
            'fields'      => [
                ICmsPage::FIELD_PAGE_SLUG  => [],
                ICmsPage::FIELD_PAGE_META_TITLE => [],
                ICmsPage::FIELD_PAGE_META_DESCRIPTION => [],
                ICmsPage::FIELD_PAGE_META_KEYWORDS => [],
                ICmsPage::FIELD_PAGE_H1 => [],
                ICmsPage::FIELD_PAGE_CONTENTS => [],
                ICmsPage::FIELD_PAGE_LAYOUT => []
            ]
        ]
    ]
];