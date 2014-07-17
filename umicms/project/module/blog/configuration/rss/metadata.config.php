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
use umicms\project\module\blog\model\object\BlogRssImportScenario;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_rss_import_scenario'
        ],
        'fields' => [
            BlogRssImportScenario::FIELD_RSS_URL => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'rss_url',
                'accessor' => 'getRssUrl'
            ],
            BlogRssImportScenario::FIELD_CATEGORY => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'category_id',
                'target' => 'blogCategory'
            ],
            BlogRssImportScenario::FIELD_TAGS => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'blogTag',
                'bridge' => 'rssBlogTag',
                'relatedField' => 'blogRssImportScenario',
                'targetField' => 'tag'
            ],
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogRssImportScenario',
                'fields' => [
                    BlogRssImportScenario::FIELD_RSS_URL  => [],
                    BlogRssImportScenario::FIELD_CATEGORY => [],
                    BlogRssImportScenario::FIELD_TAGS => []
                ]
            ]
        ]
    ]
);
