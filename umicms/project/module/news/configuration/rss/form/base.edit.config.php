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
use umicms\project\module\news\model\object\NewsRssImportScenario;

return [

    'options' => [
        'dictionaries' => [
            'collection.newsRssImportScenario', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                NewsRssImportScenario::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRssImportScenario::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => NewsRssImportScenario::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'importSetting',
            'elements' => [
                NewsRssImportScenario::FIELD_RSS_URL => [
                    'type' => Text::TYPE_NAME,
                    'label' => NewsRssImportScenario::FIELD_RSS_URL,
                    'options' => [
                        'dataSource' => NewsRssImportScenario::FIELD_RSS_URL
                    ],
                ],
                NewsRssImportScenario::FIELD_RUBRIC => [
                    'type' => Select::TYPE_NAME,
                    'label' => NewsRssImportScenario::FIELD_RUBRIC,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => NewsRssImportScenario::FIELD_RUBRIC
                    ],
                ],
                NewsRssImportScenario::FIELD_SUBJECTS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => NewsRssImportScenario::FIELD_SUBJECTS,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => NewsRssImportScenario::FIELD_SUBJECTS
                    ]
                ]
            ]
        ]
    ]
];