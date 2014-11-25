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

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'news_rss_import_scenario_subject'
        ],
        'fields'     => [
            'newsRssImportScenario'           => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'scenario_id',
                'target'     => 'newsRssImportScenario'
            ],
            'subject'                     => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'subject_id',
                'target'     => 'newsSubject'
            ]
        ],
        'types'      => [
            'base' => [
                'newsRssImportScenario' => [],
                'subject' => []
            ]
        ]
    ]
);
