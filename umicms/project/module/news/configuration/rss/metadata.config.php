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
use umicms\project\module\news\model\object\NewsRssImportScenario;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'news_rss_import_scenario'
        ],
        'fields'     => [
            NewsRssImportScenario::FIELD_RSS_URL => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'rss_url',
                'accessor'   => 'getRssUrl'
            ],
            NewsRssImportScenario::FIELD_RUBRIC => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'rubric_id',
                'target' => 'newsRubric'
            ],
            NewsRssImportScenario::FIELD_SUBJECTS => [
                'type'         => IField::TYPE_MANY_TO_MANY,
                'target'       => 'newsSubject',
                'bridge'       => 'rssScenarioSubject',
                'relatedField' => 'newsRssImportScenario',
                'targetField'  => 'subject'
            ],
        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\news\model\object\newsRssImportScenario',
                'fields'      => [
                    NewsRssImportScenario::FIELD_RSS_URL => [],
                    NewsRssImportScenario::FIELD_RUBRIC => [],
                    NewsRssImportScenario::FIELD_SUBJECTS => []
                ]
            ]
        ]
    ]
);
