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
use umicms\project\module\blog\api\object\BlogRssImportScenario;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogRssImportScenario', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BlogRssImportScenario::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogRssImportScenario::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogRssImportScenario::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'importSetting',
            'elements' => [
                BlogRssImportScenario::FIELD_RSS_URL => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogRssImportScenario::FIELD_RSS_URL,
                    'options' => [
                        'dataSource' => BlogRssImportScenario::FIELD_RSS_URL
                    ],
                ],
                BlogRssImportScenario::FIELD_CATEGORY => [
                    'type' => Select::TYPE_NAME,
                    'lazy' => true,
                    'label' => BlogRssImportScenario::FIELD_CATEGORY,
                    'options' => [
                        'dataSource' => BlogRssImportScenario::FIELD_CATEGORY
                    ],
                ],
                BlogRssImportScenario::FIELD_TAGS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => BlogRssImportScenario::FIELD_TAGS,
                    'options' => [
                        'dataSource' => BlogRssImportScenario::FIELD_TAGS
                    ]
                ]
            ]
        ]
    ]
];