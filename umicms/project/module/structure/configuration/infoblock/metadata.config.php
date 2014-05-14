<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\orm\metadata\IObjectType;
use umi\validation\IValidatorFactory;
use umicms\project\module\structure\api\object\BaseInfoBlock;
use umicms\project\module\structure\api\object\InfoBlock;

return [
    'dataSource' => [
        'sourceName' => 'umi_infoblock'
    ],
    'fields' => [

        BaseInfoBlock::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        BaseInfoBlock::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        BaseInfoBlock::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        BaseInfoBlock::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        BaseInfoBlock::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'display_name'
                ],
                'en-US' => [
                    'columnName' => 'display_name_en'
                ]
            ]
        ],
        BaseInfoBlock::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created'
        ],
        BaseInfoBlock::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated'
        ],
        BaseInfoBlock::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BaseInfoBlock::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],

        InfoBlock::FIELD_PHONE_NUMBER => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'phone_number',
        ],
        InfoBlock::FIELD_EMAIL => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'email',
        ],
        InfoBlock::FIELD_ADDRESS => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'address',
        ],
        InfoBlock::FIELD_LOGO => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'logo',
        ],
        InfoBlock::FIELD_COPYRIGHT => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'copyright',
        ],
        InfoBlock::FIELD_COUNTER => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'counter',
        ],
        InfoBlock::FIELD_WIDGET_VK => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'widget_vk',
        ],
        InfoBlock::FIELD_WIDGET_FB => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'widget_fb',
        ],
        InfoBlock::FIELD_WIDGET_TW => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'widget_tw',
        ],
        InfoBlock::FIELD_SHARE => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'share',
        ],
        InfoBlock::FIELD_SOC_GROUP_LINK => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'soc_group_link',
        ]
    ],
    'types' => [
        IObjectType::BASE => [
            'objectClass' => 'umicms\project\module\structure\api\object\BaseInfoBlock',
            'fields' => [
                BaseInfoBlock::FIELD_IDENTIFY,
                BaseInfoBlock::FIELD_GUID,
                BaseInfoBlock::FIELD_TYPE,
                BaseInfoBlock::FIELD_VERSION,
                BaseInfoBlock::FIELD_DISPLAY_NAME,
                BaseInfoBlock::FIELD_CREATED,
                BaseInfoBlock::FIELD_UPDATED,
                BaseInfoBlock::FIELD_OWNER,
                BaseInfoBlock::FIELD_EDITOR
            ]
        ],
        InfoBlock::TYPE => [
            'objectClass' => 'umicms\project\module\structure\api\object\InfoBlock',
            'fields' => [
                InfoBlock::FIELD_IDENTIFY,
                InfoBlock::FIELD_GUID,
                InfoBlock::FIELD_TYPE,
                InfoBlock::FIELD_VERSION,
                InfoBlock::FIELD_DISPLAY_NAME,
                InfoBlock::FIELD_CREATED,
                InfoBlock::FIELD_UPDATED,
                InfoBlock::FIELD_OWNER,
                InfoBlock::FIELD_EDITOR,
                InfoBlock::FIELD_PHONE_NUMBER,
                InfoBlock::FIELD_EMAIL,
                InfoBlock::FIELD_ADDRESS,
                InfoBlock::FIELD_LOGO,
                InfoBlock::FIELD_COPYRIGHT,
                InfoBlock::FIELD_COUNTER,
                InfoBlock::FIELD_WIDGET_VK,
                InfoBlock::FIELD_WIDGET_FB,
                InfoBlock::FIELD_WIDGET_TW,
                InfoBlock::FIELD_SHARE,
                InfoBlock::FIELD_SOC_GROUP_LINK,
            ]
        ]
    ]
];
