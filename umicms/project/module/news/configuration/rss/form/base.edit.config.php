<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\MultiSelect;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\news\api\object\RssItem;

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
                RssItem::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssItem::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => RssItem::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                RssItem::FIELD_CHARSET_RSS => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssItem::FIELD_CHARSET_RSS,
                    'options' => [
                        'dataSource' => RssItem::FIELD_CHARSET_RSS
                    ],
                ],
                RssItem::FIELD_RUBRIC => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssItem::FIELD_RUBRIC,
                    'options' => [
                        'dataSource' => RssItem::FIELD_RUBRIC
                    ],
                ],
                RssItem::FIELD_SUBJECTS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => RssItem::FIELD_SUBJECTS,
                    'options' => [
                        'dataSource' => RssItem::FIELD_SUBJECTS
                    ]
                ]
            ]
        ]
    ]
];