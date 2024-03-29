<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Types\Type;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/scheme/collection.config.php',
    [
        'name' => 'surveys_answer',
        'columns'     =>  [
            'survey_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'votes' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ],

        ],
        'indexes'     => [
            'rubric' => [
                'columns' => [
                    'survey_id' => []
                ]
            ]
        ],
        'constraints' => [
            'answer_to_survey' => [
                'foreignTable' => 'surveys_survey',
                'columns' => [
                    'survey_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            ]
        ]
    ]
);
