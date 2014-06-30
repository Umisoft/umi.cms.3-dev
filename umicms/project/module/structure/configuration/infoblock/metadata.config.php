<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\orm\metadata\IObjectType;
use umi\validation\IValidatorFactory;
use umicms\project\Environment;
use umicms\project\module\structure\model\object\BaseInfoBlock;
use umicms\project\module\structure\model\object\InfoBlock;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'infoblock'
        ],
        'fields'     => [
            BaseInfoBlock::FIELD_INFOBLOCK_NAME => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
            InfoBlock::FIELD_PHONE_NUMBER       => [
                'type'          => IField::TYPE_STRING,
                'columnName'    => 'phone_number',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'phone_number'
                    ],
                    'en-US' => [
                        'columnName' => 'phone_number_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_EMAIL              => [
                'type'          => IField::TYPE_STRING,
                'columnName'    => 'email',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'email'
                    ],
                    'en-US' => [
                        'columnName' => 'email_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_ADDRESS            => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'address',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'address'
                    ],
                    'en-US' => [
                        'columnName' => 'address_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_LOGO               => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'logo',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'logo'
                    ],
                    'en-US' => [
                        'columnName' => 'logo_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_COPYRIGHT          => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'copyright',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'copyright'
                    ],
                    'en-US' => [
                        'columnName' => 'copyright_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_COUNTER            => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'counter',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'counter'
                    ],
                    'en-US' => [
                        'columnName' => 'counter_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_WIDGET_VK          => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'widget_vk',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'widget_vk'
                    ],
                    'en-US' => [
                        'columnName' => 'widget_vk_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_WIDGET_FACEBOOK    => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'widget_facebook',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'widget_facebook'
                    ],
                    'en-US' => [
                        'columnName' => 'widget_facebook_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_WIDGET_TWITTER     => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'widget_twitter',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'widget_twitter'
                    ],
                    'en-US' => [
                        'columnName' => 'widget_twitter_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_SHARE              => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'share',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'share'
                    ],
                    'en-US' => [
                        'columnName' => 'share_en'
                    ]
                ]
            ],
            InfoBlock::FIELD_SOCIAL_GROUP_LINK  => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'social_group_link',
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
        'types'      => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\structure\model\object\BaseInfoBlock',
                'fields'      => [
                    BaseInfoBlock::FIELD_INFOBLOCK_NAME,
                ]
            ],
            InfoBlock::TYPE   => [
                'objectClass' => 'umicms\project\module\structure\model\object\InfoBlock',
                'fields'      => [
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
                    InfoBlock::FIELD_SOCIAL_GROUP_LINK
                ]
            ]
        ]
    ]
);
