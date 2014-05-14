<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Text;
use umi\form\element\Textarea;
use umi\form\fieldset\FieldSet;
use umicms\project\module\structure\api\object\InfoBlock;

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
                InfoBlock::FIELD_WIDGET_FB => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_WIDGET_FB,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_WIDGET_FB
                    ],
                ],
                InfoBlock::FIELD_WIDGET_TW => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_WIDGET_TW,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_WIDGET_TW
                    ],
                ],
                InfoBlock::FIELD_SHARE => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_SHARE,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_SHARE
                    ],
                ],
                InfoBlock::FIELD_SOC_GROUP_LINK => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => InfoBlock::FIELD_SOC_GROUP_LINK,
                    'options' => [
                        'dataSource' => InfoBlock::FIELD_SOC_GROUP_LINK
                    ],
                ],
            ]
        ]
    ]
];