<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatches\model\object\Release;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatches_release'
        ],
        'fields'     => [
            Release::FIELD_DISPATCH             => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'dispatch_id',
                'target'     => 'dispatch'
            ],
            Release::FIELD_SUBJECT              => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'subject'
            ],
            Release::FIELD_HEADER               => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'header'
            ],
            Release::FIELD_MESSAGE              => [
                'type'       => IField::TYPE_TEXT,
                'columnName' => 'message'
            ],
            Release::FIELD_TEMPLATE             => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'template_id',
                'target'     => 'dispatchTemplate'
            ],
            Release::FIELD_STATUS               => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'status_id',
                'target'     => 'dispatchReleaseStatus'
            ],
            Release::FIELD_START_TIME           => [
                'type'       => IField::TYPE_DATE_TIME,
                'columnName' => 'start_time'
            ],
            Release::FIELD_FINISH_TIME          => [
                'type'       => IField::TYPE_DATE_TIME,
                'columnName' => 'finish_time'
            ],
            Release::FIELD_SENT_MESSAGE_COUNT   => [
                'type'       => IField::TYPE_INTEGER,
                'columnName' => 'sent_message_count'
            ],
            Release::FIELD_VIEWED_MESSAGE_COUNT => [
                'type'       => IField::TYPE_INTEGER,
                'columnName' => 'viewed_message_count'
            ],
            Release::FIELD_UNSUBSCRIPTION_COUNT => [
                'type'       => IField::TYPE_INTEGER,
                'columnName' => 'unsubscription_count'
            ],
            Release::FIELD_VIEW_PERCENT         => [
                'type'         => IField::TYPE_DELAYED,
                'columnName'   => 'view_percent',
                'defaultValue' => 0,
                'dataType'     => 'integer', // TODO: bug cms-918
                'formula'      => 'calculateViewPercent',
                'readOnly'     => true,
            ],
        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Release',
                'fields'      => [
                    Release::FIELD_DISPATCH             => [],
                    Release::FIELD_SUBJECT              => [],
                    Release::FIELD_HEADER               => [],
                    Release::FIELD_MESSAGE              => [],
                    Release::FIELD_TEMPLATE             => [],
                    Release::FIELD_STATUS               => [],
                    Release::FIELD_START_TIME           => [],
                    Release::FIELD_FINISH_TIME          => [],
                    Release::FIELD_SENT_MESSAGE_COUNT   => [],
                    Release::FIELD_VIEWED_MESSAGE_COUNT => [],
                    Release::FIELD_UNSUBSCRIPTION_COUNT => [],
                    Release::FIELD_VIEW_PERCENT         => [],
                ]
            ]
        ],
    ]
);
