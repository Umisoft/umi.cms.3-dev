<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Checkbox;
use umi\form\element\MultiSelect;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\api\object\BlogTag;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogTag', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BlogTag::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogTag::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_DISPLAY_NAME
                    ],
                ],
                BlogTag::FIELD_PAGE_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogTag::FIELD_PAGE_SLUG,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_PAGE_SLUG
                    ],
                ],
                BlogTag::FIELD_ACTIVE => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => BlogTag::FIELD_ACTIVE,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_ACTIVE
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                BlogTag::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogTag::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_PAGE_H1
                    ],
                ],
                BlogTag::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogTag::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_PAGE_META_TITLE
                    ],
                ],
                BlogTag::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogTag::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                BlogTag::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogTag::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                BlogTag::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => BlogTag::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_PAGE_CONTENTS
                    ]
                ],

                'rssImportItem' => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => BlogTag::FIELD_RSS,
                    'options' => [
                        'dataSource' => BlogTag::FIELD_RSS
                    ]
                ],
            ]
        ]
    ]
];