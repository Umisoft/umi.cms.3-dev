<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\surveys\model\object\Answer;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'surveys_answer'
        ],
        'fields' => [
            Answer::FIELD_SURVEY => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'survey_id',
                'target' => 'survey'
            ],
            Answer::FIELD_COUNTER => [
                'type' => IField::TYPE_COUNTER,
                'columnName' => 'counter',
                'defaultValue' => 0
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\surveys\model\object\Answer',
                'fields' => [
                    Answer::FIELD_SURVEY => [],
                    Answer::FIELD_COUNTER => []
                ]
            ]
        ]
    ]
);
