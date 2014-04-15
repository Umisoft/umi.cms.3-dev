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
use umicms\project\module\blog\api\object\RssImportPost;

return [

    'options' => [
        'dictionaries' => [
            'collection.rssImportPost', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                RssImportPost::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssImportPost::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => RssImportPost::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'importSetting',
            'elements' => [
                RssImportPost::FIELD_RSS_URL => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssImportPost::FIELD_RSS_URL,
                    'options' => [
                        'dataSource' => RssImportPost::FIELD_RSS_URL
                    ],
                ],
                RssImportPost::FIELD_CATEGORY => [
                    'type' => Select::TYPE_NAME,
                    'label' => RssImportPost::FIELD_CATEGORY,
                    'options' => [
                        'dataSource' => RssImportPost::FIELD_CATEGORY
                    ],
                ],
                RssImportPost::FIELD_TAGS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => RssImportPost::FIELD_TAGS,
                    'options' => [
                        'dataSource' => RssImportPost::FIELD_TAGS
                    ]
                ]
            ]
        ]
    ]
];