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
use umicms\project\module\surveys\model\object\Survey;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/pageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'surveys_survey'
        ],
        'fields' => [
            Survey::FIELD_ANSWERS => [
                'type' => IField::TYPE_HAS_MANY,
                'columnName' => 'answers',
                'targetField' => Answer::FIELD_SURVEY,
                'target' => 'answer'
            ],
            Survey::FIELD_MULTIPLE_CHOICE => [
                'type' => IField::TYPE_BOOL,
                'columnName' => 'multiple_choice',
                'defaultValue' => 0
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\surveys\model\object\Survey',
                'fields' => [
                    Survey::FIELD_ANSWERS => [],
                    Survey::FIELD_MULTIPLE_CHOICE => []
                ]
            ]
        ]
    ]
);
