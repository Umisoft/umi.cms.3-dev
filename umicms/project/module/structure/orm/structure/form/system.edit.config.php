<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\structure\object\SystemPage;

return [

    'options' => [
        'dictionaries' => [
            'collection.structure', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                SystemPage::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => SystemPage::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => SystemPage::FIELD_DISPLAY_NAME
                    ],
                ],
                SystemPage::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => SystemPage::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'dataSource' => SystemPage::FIELD_PAGE_LAYOUT
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                SystemPage::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => SystemPage::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => SystemPage::FIELD_PAGE_H1
                    ],
                ],
                SystemPage::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => SystemPage::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => SystemPage::FIELD_PAGE_META_TITLE
                    ],
                ],
                SystemPage::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => SystemPage::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => SystemPage::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                SystemPage::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => SystemPage::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => SystemPage::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                SystemPage::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => SystemPage::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => SystemPage::FIELD_PAGE_CONTENTS
                    ]
                ]
            ]
        ]
    ]
];