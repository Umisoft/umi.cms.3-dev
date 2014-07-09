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
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\news\model\object\NewsRubric;

return [

    'options' => [
        'dictionaries' => [
            'collection.newsRubric', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                NewsRubric::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRubric::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_DISPLAY_NAME
                    ],
                ],
                NewsRubric::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => NewsRubric::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'choices' => [
                            null => 'Default or inherited layout'
                        ],
                        'lazy' => true,
                        'dataSource' => NewsRubric::FIELD_PAGE_LAYOUT
                    ],
                ],
                NewsRubric::FIELD_PAGE_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRubric::FIELD_PAGE_SLUG,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_PAGE_SLUG
                    ],
                ],
                NewsRubric::FIELD_ACTIVE => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => NewsRubric::FIELD_ACTIVE,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_ACTIVE
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                NewsRubric::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRubric::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_PAGE_H1
                    ],
                ],
                NewsRubric::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRubric::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_PAGE_META_TITLE
                    ],
                ],
                NewsRubric::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRubric::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                NewsRubric::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRubric::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                NewsRubric::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => NewsRubric::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => NewsRubric::FIELD_PAGE_CONTENTS
                    ]
                ]
            ]
        ]
    ]
];