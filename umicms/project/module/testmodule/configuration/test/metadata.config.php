<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\module\testmodule\api\object\TestObject;

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
            ]
        ],
        TestObject::TEXT => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::TEXT],
        TestObject::TEXTAREA => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::TEXTAREA],
        TestObject::SELECT => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::SELECT],
        TestObject::PASSWORD => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::PASSWORD],
        TestObject::CHECKBOX => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::CHECKBOX],

        TestObject::DATE => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::DATE],
        TestObject::DATE_TIME => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::DATE_TIME],
        TestObject::EMAIL => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::EMAIL],
        TestObject::NUMBER => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::NUMBER],
        TestObject::TIME => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::TIME],
        TestObject::FILE => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::FILE],
        TestObject::IMAGE => ['type' => IField::TYPE_STRING, 'columnName' => TestObject::IMAGE]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\testmodule\api\object\TestObject',
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
