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
use umicms\project\module\testmodule\model\object\TestObject;

return [
    'dataSource' => [
        'sourceName' => 'umi_test'
    ],
    'fields' => [

        TestObject::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        TestObject::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        TestObject::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        TestObject::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        TestObject::FIELD_DISPLAY_NAME => [
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
        TestObject::TEXT => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::TEXT],
        TestObject::TEXTAREA => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::TEXTAREA],
        TestObject::SELECT => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::SELECT],
        TestObject::MULTISELECT => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::MULTISELECT, 'accessor' => 'getMultiSelectValue', 'mutator' => 'setMultiSelectValue'],
        TestObject::PASSWORD => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::PASSWORD],
        TestObject::CHECKBOX => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::CHECKBOX],

        TestObject::DATE => ['type' => IField::TYPE_DATE, 'columnName' => TestObject::DATE],
        TestObject::TIME => ['type' => IField::TYPE_TIME, 'columnName' => TestObject::TIME],
        TestObject::DATE_TIME => ['type' => IField::TYPE_DATE_TIME, 'columnName' => TestObject::DATE_TIME],
        TestObject::EMAIL => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::EMAIL],
        TestObject::NUMBER => ['type' => IField::TYPE_INTEGER, 'columnName' => TestObject::NUMBER],
        TestObject::FILE => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::FILE],
        TestObject::IMAGE => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::IMAGE]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\testmodule\model\object\TestObject',
            'fields' => [
                TestObject::FIELD_IDENTIFY,
                TestObject::FIELD_GUID,
                TestObject::FIELD_TYPE,
                TestObject::FIELD_VERSION,
                TestObject::FIELD_DISPLAY_NAME,

                TestObject::TEXT,
                TestObject::TEXTAREA,
                TestObject::SELECT,
                TestObject::PASSWORD,
                TestObject::CHECKBOX,

                TestObject::DATE,
                TestObject::DATE_TIME,
                TestObject::EMAIL,
                TestObject::NUMBER,
                TestObject::TIME,
                TestObject::FILE,
                TestObject::IMAGE
            ]
        ]
    ]
];
