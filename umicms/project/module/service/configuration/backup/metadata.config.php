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
use umicms\project\module\service\model\object\Backup;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'backup'
        ],
        'fields' => [
            Backup::FIELD_OBJECT_ID => [
                'type' => IField::TYPE_INTEGER,
                'columnName' => 'object_id'
            ],
            Backup::FIELD_COLLECTION_NAME => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'collection_name'
            ],
            Backup::FIELD_DATA => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'data',
                'accessor' => 'getData',
                'readOnly' => true
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\service\model\object\Backup',
                'fields' => [
                    Backup::FIELD_OBJECT_ID => [],
                    Backup::FIELD_COLLECTION_NAME => [],
                    Backup::FIELD_DATA => []
                ]
            ]
        ]
    ]
);
