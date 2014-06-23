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
use umicms\project\module\news\api\object\NewsSubject;

return [

    'options' => [
        'dictionaries' => [
            'collection.newsSubject', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                NewsSubject::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsSubject::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_DISPLAY_NAME
                    ],
                ],
                NewsSubject::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => NewsSubject::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => NewsSubject::FIELD_PAGE_LAYOUT
                    ],
                ],
                NewsSubject::FIELD_PAGE_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsSubject::FIELD_PAGE_SLUG,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_SLUG
                    ],
                ],
                NewsSubject::FIELD_ACTIVE => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => NewsSubject::FIELD_ACTIVE,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_ACTIVE
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                NewsSubject::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsSubject::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_H1
                    ],
                ],
                NewsSubject::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsSubject::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_META_TITLE
                    ],
                ],
                NewsSubject::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsSubject::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                NewsSubject::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsSubject::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                NewsSubject::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => NewsSubject::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_CONTENTS
                    ]
                ],

                'newsRssImportScenario' => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => NewsSubject::FIELD_RSS,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_RSS
                    ]
                ],
            ]
        ]
    ]
];