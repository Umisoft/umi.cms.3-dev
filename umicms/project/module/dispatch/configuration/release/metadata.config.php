<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatch\model\object\Release;
use umicms\project\module\dispatch\model\object\Dispatch;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatch_release'
        ],
        'fields'      => [
            Release::FIELD_COUNT_SEND_MESSAGE => [
                'type' => IField::TYPE_INTEGER,
                'columnName' => 'count_views'
            ],
            Release::FIELD_COUNT_VIEWS => [
                'type' => IField::TYPE_INTEGER,
                'columnName' => 'count_send_message'
            ],
            Release::FIELD_PERCENT_READS => [
                'type' => IField::TYPE_DELAYED,
                'columnName' => 'percent_reads',
                'defaultValue' => 0,
                'dataType'     => 'integer',
                'formula'      => 'calculatePercentViews',
                'readOnly'     => true,
            ],
            Release::FIELD_DISPATCHES => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'release',
                'targetField' => Dispatch::FIELD_RELEASE
            ]
        ],
        'types'  => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatch\model\object\Release',
                'fields'      => [
                    Release::FIELD_COUNT_SEND_MESSAGE => [],
                    Release::FIELD_COUNT_VIEWS => [],
                    Release::FIELD_PERCENT_READS => [],
                ]
            ]
        ],
    ]
);
