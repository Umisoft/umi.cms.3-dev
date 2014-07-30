<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\blog\model\object\BlogRssImportScenario;

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
                    'label' => BlogRssImportScenario::FIELD_CATEGORY,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogRssImportScenario::FIELD_CATEGORY
                    ],
                ],
                BlogRssImportScenario::FIELD_TAGS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => BlogRssImportScenario::FIELD_TAGS,
                    'options' => [
                        'dataSource' => BlogRssImportScenario::FIELD_TAGS,
                        'lazy' => true
                    ]
                ],
                BlogRssImportScenario::FIELD_AUTHOR => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogRssImportScenario::FIELD_AUTHOR,
                    'options' => [
                        'dataSource' => BlogRssImportScenario::FIELD_AUTHOR,
                        'lazy' => true
                    ]
                ],
                BlogRssImportScenario::FIELD_POST_STATUS => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogRssImportScenario::FIELD_POST_STATUS,
                    'options' => [
                        'dataSource' => BlogRssImportScenario::FIELD_POST_STATUS,
                        'lazy' => true
                    ]
                ]
            ]
        ]
    ]
];