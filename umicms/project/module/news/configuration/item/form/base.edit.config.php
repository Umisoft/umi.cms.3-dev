<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\html5\DateTime;
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\news\api\object\NewsItem;

return [

    'options' => [
        'dictionaries' => [
            'collection.newsItem', 'collection'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                NewsItem::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsItem::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_DISPLAY_NAME
                    ],
                ],
                NewsItem::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => NewsItem::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_PAGE_LAYOUT
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                NewsItem::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsItem::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_PAGE_H1
                    ],
                ],
                NewsItem::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsItem::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_PAGE_META_TITLE
                    ],
                ],
                NewsItem::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsItem::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                NewsItem::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsItem::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                NewsItem::FIELD_RUBRIC => [
                    'type' => Select::TYPE_NAME,
                    'label' => NewsItem::FIELD_RUBRIC,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_RUBRIC
                    ]
                ],

                NewsItem::FIELD_SUBJECTS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => NewsItem::FIELD_SUBJECTS,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_SUBJECTS
                    ]
                ],

                NewsItem::FIELD_DATE => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => NewsItem::FIELD_DATE,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_DATE
                    ]
                ],

                NewsItem::FIELD_ANNOUNCEMENT => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => NewsItem::FIELD_ANNOUNCEMENT,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_ANNOUNCEMENT
                    ]
                ],

                NewsItem::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => NewsItem::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_PAGE_CONTENTS
                    ]
                ],

                NewsItem::FIELD_SOURCE => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsItem::FIELD_SOURCE,
                    'options' => [
                        'dataSource' => NewsItem::FIELD_SOURCE
                    ]
                ]
            ],

        ]
    ]
];