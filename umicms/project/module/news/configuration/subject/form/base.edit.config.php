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
use umicms\project\module\news\api\object\NewsSubject;

return [

    'options' => [
        'dictionaries' => [
            'collection.newsSubject', 'collection'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [
                NewsSubject::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_DISPLAY_NAME
                    ],
                ],
                NewsSubject::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_LAYOUT
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [
                NewsSubject::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_H1
                    ],
                ],
                NewsSubject::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_META_TITLE
                    ],
                ],
                NewsSubject::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                NewsSubject::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [

                NewsSubject::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'options' => [
                        'dataSource' => NewsSubject::FIELD_PAGE_CONTENTS
                    ]
                ]
            ]
        ]
    ]
];