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
        BaseInfoBlock::FIELD_LOCKED => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'locked',
            'readOnly' => true,
            'defaultValue' => 0
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
        InfoBlock::FIELD_PHONE_NUMBER => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'phone_number',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'phone_number'
                ],
                'en-US' => [
                    'columnName' => 'phone_number_en'
                ]
            ]
        ],
        InfoBlock::FIELD_EMAIL => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'email',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'email'
                ],
                'en-US' => [
                    'columnName' => 'email_en'
                ]
            ]
        ],
        InfoBlock::FIELD_ADDRESS => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'address',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'address'
                ],
                'en-US' => [
                    'columnName' => 'address_en'
                ]
            ]
        ],
        InfoBlock::FIELD_LOGO => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'logo',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'logo'
                ],
                'en-US' => [
                    'columnName' => 'logo_en'
                ]
            ]
        ],
        InfoBlock::FIELD_COPYRIGHT => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'copyright',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'copyright'
                ],
                'en-US' => [
                    'columnName' => 'copyright_en'
                ]
            ]
        ],
        InfoBlock::FIELD_COUNTER => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'counter',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'counter'
                ],
                'en-US' => [
                    'columnName' => 'counter_en'
                ]
            ]
        ],
        InfoBlock::FIELD_WIDGET_VK => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'widget_vk',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'widget_vk'
                ],
                'en-US' => [
                    'columnName' => 'widget_vk_en'
                ]
            ]
        ],
        InfoBlock::FIELD_WIDGET_FACEBOOK => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'widget_facebook',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'widget_facebook'
                ],
                'en-US' => [
                    'columnName' => 'widget_facebook_en'
                ]
            ]
        ],
        InfoBlock::FIELD_WIDGET_TWITTER => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'widget_twitter',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'widget_twitter'
                ],
                'en-US' => [
                    'columnName' => 'widget_twitter_en'
                ]
            ]
        ],
        InfoBlock::FIELD_SHARE => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'share',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'share'
                ],
                'en-US' => [
                    'columnName' => 'share_en'
                ]
            ]
        ],
        InfoBlock::FIELD_SOCIAL_GROUP_LINK => [
            'type' => IField::TYPE_TEXT,
            'columnName' => 'social_group_link',
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'social_group_link'
                ],
                'en-US' => [
                    'columnName' => 'social_group_link_en'
                ]
            ]
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
                BaseInfoBlock::FIELD_LOCKED,
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
                InfoBlock::FIELD_LOCKED,
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
                InfoBlock::FIELD_WIDGET_FACEBOOK,
                InfoBlock::FIELD_WIDGET_TWITTER,
                InfoBlock::FIELD_SHARE,
                InfoBlock::FIELD_SOCIAL_GROUP_LINK,
            ]
        ]
    ]
];
