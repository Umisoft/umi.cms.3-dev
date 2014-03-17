<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\structure\object\Layout;

return [
    'dataSource' => [
        'sourceName' => 'umi_layout'
    ],
    'fields'     => [

        Layout::FIELD_IDENTIFY     => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId'
        ],
        Layout::FIELD_GUID         => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'mutator'    => 'setGuid'
        ],
        Layout::FIELD_TYPE         => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        Layout::FIELD_VERSION      => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'mutator'      => 'setVersion',
            'defaultValue' => 1
        ],
        Layout::FIELD_DISPLAY_NAME => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        Layout::FIELD_ACTIVE       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        Layout::FIELD_LOCKED       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'   => true,
            'defaultValue' => 0
        ],
        Layout::FIELD_CREATED      => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created'],
        Layout::FIELD_UPDATED      => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated'],
        Layout::FIELD_FILE_NAME    => ['type' => IField::TYPE_STRING, 'columnName' => 'file_name'],

    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\structure\object\Layout',
            'fields'      => [
                Layout::FIELD_IDENTIFY,
                Layout::FIELD_GUID,
                Layout::FIELD_TYPE,
                Layout::FIELD_VERSION,
                Layout::FIELD_DISPLAY_NAME,
                Layout::FIELD_ACTIVE,
                Layout::FIELD_LOCKED,
                Layout::FIELD_CREATED,
                Layout::FIELD_UPDATED,
                Layout::FIELD_FILE_NAME
            ]
        ]
    ]
];
