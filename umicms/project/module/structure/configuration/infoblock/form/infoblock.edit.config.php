<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Text;
use umi\form\element\Textarea;
use umi\form\fieldset\FieldSet;
use umicms\project\module\structure\model\object\InfoBlock;

return [

    'options' => [
        'dictionaries' => [
            'collection.infoblock', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                InfoBlock::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => InfoBlock::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_DISPLAY_NAME
                    ],
                ],
                InfoBlock::FIELD_INFOBLOCK_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => InfoBlock::FIELD_INFOBLOCK_NAME,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_INFOBLOCK_NAME
                    ],
                ]
            ]
        ],

        'contact' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contact',
            'elements' => [
                InfoBlock::FIELD_PHONE_NUMBER => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_PHONE_NUMBER,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_PHONE_NUMBER
                    ],
                ],
                InfoBlock::FIELD_EMAIL => [
                    'type' => Text::TYPE_NAME,
                    'label' => InfoBlock::FIELD_EMAIL,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_EMAIL
                    ],
                ],
                InfoBlock::FIELD_ADDRESS => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_ADDRESS,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_ADDRESS
                    ],
                ]
            ]
        ],

        'about' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'about',
            'elements' => [
                InfoBlock::FIELD_LOGO => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_LOGO,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_LOGO
                    ],
                ],
                InfoBlock::FIELD_COPYRIGHT => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_COPYRIGHT,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_COPYRIGHT
                    ],
                ]
            ]
        ],

        'social' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'social',
            'elements' => [
                InfoBlock::FIELD_COUNTER => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_COUNTER,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_COUNTER
                    ],
                ],
                InfoBlock::FIELD_WIDGET_VK => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_WIDGET_VK,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_WIDGET_VK
                    ],
                ],
                InfoBlock::FIELD_WIDGET_FACEBOOK => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_WIDGET_FACEBOOK,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_WIDGET_FACEBOOK
                    ],
                ],
                InfoBlock::FIELD_WIDGET_TWITTER => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_WIDGET_TWITTER,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_WIDGET_TWITTER
                    ],
                ],
                InfoBlock::FIELD_SHARE => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_SHARE,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_SHARE
                    ],
                ],
                InfoBlock::FIELD_SOCIAL_GROUP_LINK => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_SOCIAL_GROUP_LINK,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_SOCIAL_GROUP_LINK
                    ],
                ],
            ]
        ]
    ]
];