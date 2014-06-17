<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\module\structure\model\object\Layout;

return [
    'dataSource' => [
        'sourceName' => 'umi_layout'
    ],
    'fields'     => [

        Layout::FIELD_IDENTIFY     => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId',
            'readOnly'   => true
        ],
        Layout::FIELD_GUID         => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'readOnly'   => true
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
            'readOnly'   => true,
            'defaultValue' => 1
        ],
        Layout::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        Layout::FIELD_LOCKED       => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'   => true,
            'defaultValue' => 0
        ],
        Layout::FIELD_CREATED      => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created', 'readOnly'   => true],
        Layout::FIELD_UPDATED      => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated', 'readOnly'   => true],
        Layout::FIELD_FILE_NAME    => ['type' => IField::TYPE_STRING, 'columnName' => 'file_name'],
        Layout::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        Layout::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ]
    ],
    'types'      => [
        'base' => [
            'objectClass' => 'umicms\project\module\structure\model\object\Layout',
            'fields'      => [
                Layout::FIELD_IDENTIFY,
                Layout::FIELD_GUID,
                Layout::FIELD_TYPE,
                Layout::FIELD_VERSION,
                Layout::FIELD_DISPLAY_NAME,
                Layout::FIELD_LOCKED,
                Layout::FIELD_CREATED,
                Layout::FIELD_UPDATED,
                Layout::FIELD_FILE_NAME,
                Layout::FIELD_OWNER,
                Layout::FIELD_EDITOR
            ]
        ]
    ]
];
