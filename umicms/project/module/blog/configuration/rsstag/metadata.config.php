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
use umicms\project\Environment;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_rss_tag'
        ],
        'fields' => [
            'blogRssImportScenario' => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'scenario_id',
                'target' => 'blogRssImportScenario'
            ],
            'tag' => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'tag_id',
                'target' => 'blogTag'
            ]

        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\orm\object\CmsLinkObject',
                'fields' => [
                    'blogRssImportScenario' => [],
                    'tag' => []
                ]
            ]
        ]
    ]
);
