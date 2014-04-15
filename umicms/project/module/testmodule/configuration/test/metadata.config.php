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
use umicms\project\module\testmodule\api\object\test;

return [
    'dataSource' => [
        'sourceName' => 'umi_Test'
    ],
    'fields' => [

        Test::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        Test::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        Test::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        Test::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        Test::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ]
        ],
        Test::TEXT => ['type' => IField::TYPE_STRING, 'columnName' => Test::TEXT],
        Test::TEXTAREA => ['type' => IField::TYPE_STRING, 'columnName' => Test::TEXTAREA],
        Test::SELECT => ['type' => IField::TYPE_STRING, 'columnName' => Test::SELECT],
        Test::PASSWORD => ['type' => IField::TYPE_STRING, 'columnName' => Test::PASSWORD],
        Test::CHECKBOX => ['type' => IField::TYPE_STRING, 'columnName' => Test::CHECKBOX],

        Test::DATE => ['type' => IField::TYPE_STRING, 'columnName' => Test::DATE],
        Test::DATE_TIME => ['type' => IField::TYPE_STRING, 'columnName' => Test::DATE_TIME],
        Test::EMAIL => ['type' => IField::TYPE_STRING, 'columnName' => Test::EMAIL],
        Test::NUMBER => ['type' => IField::TYPE_STRING, 'columnName' => Test::NUMBER],
        Test::TIME => ['type' => IField::TYPE_STRING, 'columnName' => Test::TIME],
        Test::FILE => ['type' => IField::TYPE_STRING, 'columnName' => Test::FILE],
        Test::IMAGE => ['type' => IField::TYPE_STRING, 'columnName' => Test::IMAGE]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\testmodule\api\object\Test',
            'fields' => [
                Test::FIELD_IDENTIFY,
                Test::FIELD_GUID,
                Test::FIELD_TYPE,
                Test::FIELD_VERSION,
                Test::FIELD_DISPLAY_NAME,

                Test::TEXT,
                Test::TEXTAREA,
                Test::SELECT,
                Test::PASSWORD,
                Test::CHECKBOX,

                Test::DATE,
                Test::DATE_TIME,
                Test::EMAIL,
                Test::NUMBER,
                Test::TIME,
                Test::FILE,
                Test::IMAGE
            ]
        ]
    ]
];
