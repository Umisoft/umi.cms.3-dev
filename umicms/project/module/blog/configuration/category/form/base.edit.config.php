<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\api\object\BlogCategory;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogCategory', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BlogCategory::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogCategory::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_DISPLAY_NAME
                    ],
                ],
                BlogCategory::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogCategory::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_PAGE_LAYOUT
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                BlogCategory::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogCategory::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_PAGE_H1
                    ],
                ],
                BlogCategory::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogCategory::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_PAGE_META_TITLE
                    ],
                ],
                BlogCategory::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogCategory::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                BlogCategory::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogCategory::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                BlogCategory::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => BlogCategory::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_PAGE_CONTENTS
                    ]
                ],

                BlogCategory::FIELD_POSTS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => BlogCategory::FIELD_POSTS,
                    'options' => [
                        'dataSource' => BlogCategory::FIELD_POSTS
                    ]
                ]
            ]
        ]
    ]
];