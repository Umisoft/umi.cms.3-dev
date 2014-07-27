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
use umicms\project\module\search\model\object\SearchIndex;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'search_index'
        ],
        'fields' => [
            SearchIndex::FIELD_REF_GUID => [
                'type' => IField::TYPE_GUID,
                'columnName' => 'ref_guid',
            ],
            SearchIndex::FIELD_CONTENT => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents'],
                    'en-US' => ['columnName' => 'contents_en']
                ]
            ],
            SearchIndex::FIELD_REF_COLLECTION_NAME => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'collection_name',
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\search\model\object\SearchIndex',
                'fields' => [
                    SearchIndex::FIELD_REF_GUID => [],
                    SearchIndex::FIELD_CONTENT => [],
                    SearchIndex::FIELD_REF_COLLECTION_NAME => []
                ]
            ]
        ]
    ]
);
