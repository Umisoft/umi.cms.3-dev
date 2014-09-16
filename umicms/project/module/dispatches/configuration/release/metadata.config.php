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
        'fields'      => [
            Release::FIELD_DISPATCHES => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'dispatch'
            ],
            Release::FIELD_SUBJECT => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'subject'
            ],
            Release::FIELD_MESSAGE_HEADER => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'message_header'
            ],
            Release::FIELD_MESSAGE => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'message'
            ],
            Release::FIELD_TEMPLATE_MESSAGE => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'template_message'
            ],
            Release::FIELD_SENDING_STATUS => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'sending_status'
            ],
            Release::FIELD_DATE_START => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'date_start'
            ],
            Release::FIELD_DATE_FINISH => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'date_finish'
            ],
            Release::FIELD_COUNT_SEND_MESSAGE => [
                'type' => IField::TYPE_INTEGER,
                'columnName' => 'count_send_message'
            ],
            Release::FIELD_COUNT_VIEWS => [
                'type' => IField::TYPE_INTEGER,
                'columnName' => 'count_views'
            ],
            Release::FIELD_COUNT_UNSUBSCRIBE => [
                'type' => IField::TYPE_INTEGER,
                'columnName' => 'count_unsubscribe'
            ],
            Release::FIELD_PERCENT_READS => [
                'type' => IField::TYPE_DELAYED,
                'columnName' => 'percent_reads',
                'defaultValue' => 0,
                'dataType'     => 'integer',
                'formula'      => 'calculatePercentViews',
                'readOnly'     => true,
            ],
        ],
        'types'  => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Release',
                'fields'      => [
                    Release::FIELD_DISPATCHES => [],
                    Release::FIELD_SUBJECT => [],
                    Release::FIELD_MESSAGE_HEADER => [],
                    Release::FIELD_MESSAGE => [],
                    Release::FIELD_TEMPLATE_MESSAGE => [],
                    Release::FIELD_SENDING_STATUS => [],
                    Release::FIELD_DATE_START => [],
                    Release::FIELD_DATE_FINISH => [],
                    Release::FIELD_COUNT_SEND_MESSAGE => [],
                    Release::FIELD_COUNT_VIEWS => [],
                    Release::FIELD_COUNT_UNSUBSCRIBE => [],
                    Release::FIELD_PERCENT_READS => [],
                ]
            ]
        ],
    ]
);
