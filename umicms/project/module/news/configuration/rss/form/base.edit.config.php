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
use umicms\project\module\news\api\object\RssImportScenario;

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
                RssImportScenario::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssImportScenario::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => RssImportScenario::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'importSetting',
            'elements' => [
                RssImportScenario::FIELD_RSS_URL => [
                    'type' => Text::TYPE_NAME,
                    'label' => RssImportScenario::FIELD_RSS_URL,
                    'options' => [
                        'dataSource' => RssImportScenario::FIELD_RSS_URL
                    ],
                ],
                RssImportScenario::FIELD_RUBRIC => [
                    'type' => Select::TYPE_NAME,
                    'label' => RssImportScenario::FIELD_RUBRIC,
                    'options' => [
                        'dataSource' => RssImportScenario::FIELD_RUBRIC
                    ],
                ],
                RssImportScenario::FIELD_SUBJECTS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => RssImportScenario::FIELD_SUBJECTS,
                    'options' => [
                        'dataSource' => RssImportScenario::FIELD_SUBJECTS
                    ]
                ]
            ]
        ]
    ]
];