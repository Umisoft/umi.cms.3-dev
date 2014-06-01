<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Checkbox;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\structure\api\object\StaticPage;

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
                StaticPage::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => StaticPage::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_DISPLAY_NAME
                    ],
                ],
                StaticPage::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'lazy' => true,
                    'label' => StaticPage::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_PAGE_LAYOUT
                    ],
                ],
                StaticPage::FIELD_PAGE_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => StaticPage::FIELD_PAGE_SLUG,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_PAGE_SLUG
                    ],
                ],
                StaticPage::FIELD_ACTIVE => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => StaticPage::FIELD_ACTIVE,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_ACTIVE
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                StaticPage::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => StaticPage::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_PAGE_H1
                    ],
                ],
                StaticPage::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => StaticPage::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_PAGE_META_TITLE
                    ],
                ],
                StaticPage::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => StaticPage::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                StaticPage::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => StaticPage::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                StaticPage::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => StaticPage::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => StaticPage::FIELD_PAGE_CONTENTS
                    ]
                ]
            ]
        ]
    ]
];