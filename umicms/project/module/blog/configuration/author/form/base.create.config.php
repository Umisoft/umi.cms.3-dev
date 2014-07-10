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
use umicms\project\module\blog\model\object\BlogAuthor;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogAuthor', 'collection', 'form'
        ]
    ],
    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BlogAuthor::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_DISPLAY_NAME
                    ],
                ],
                BlogAuthor::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogAuthor::FIELD_PAGE_LAYOUT
                    ],
                ],
                BlogAuthor::FIELD_PAGE_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_SLUG,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_PAGE_SLUG
                    ],
                ],
                BlogAuthor::FIELD_ACTIVE => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_ACTIVE,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_ACTIVE
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                BlogAuthor::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_PAGE_H1
                    ],
                ],
                BlogAuthor::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_PAGE_META_TITLE
                    ],
                ],
                BlogAuthor::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                BlogAuthor::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                BlogAuthor::FIELD_PROFILE => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PROFILE,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogAuthor::FIELD_PROFILE
                    ],
                ],
                BlogAuthor::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_PAGE_CONTENTS
                    ]
                ]
            ],

        ]
    ]
];