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
use umicms\project\module\news\api\object\RssImportItem;

return [

    'options' => [
        'dictionaries' => [
            'collection.rssImportItem', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                RssImportItem::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssImportItem::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => RssImportItem::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'importSetting',
            'elements' => [
                RssImportItem::FIELD_RSS_URL => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssImportItem::FIELD_RSS_URL,
                    'options' => [
                        'dataSource' => RssImportItem::FIELD_RSS_URL
                    ],
                ],
                RssImportItem::FIELD_RUBRIC => [
                    'type' => Select::TYPE_NAME,
                    'label' => RssImportItem::FIELD_RUBRIC,
                    'options' => [
                        'dataSource' => RssImportItem::FIELD_RUBRIC
                    ],
                ],
                RssImportItem::FIELD_SUBJECTS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => RssImportItem::FIELD_SUBJECTS,
                    'options' => [
                        'dataSource' => RssImportItem::FIELD_SUBJECTS
                    ]
                ]
            ]
        ]
    ]
];