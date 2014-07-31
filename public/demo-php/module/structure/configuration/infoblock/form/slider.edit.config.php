<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use project\module\structure\model\object\Slider;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
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

        'slide1' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'slide1',
            'elements' => [
                Slider::FIELD_SLIDE_NAME_1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => Slider::FIELD_SLIDE_NAME_1,
                    'options' => [
                        'dataSource' => Slider::FIELD_SLIDE_NAME_1
                    ],
                ],
                Slider::FIELD_SLIDE_CONTENT_1 => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => Slider::FIELD_SLIDE_CONTENT_1,
                    'options' => [
                        'dataSource' => Slider::FIELD_SLIDE_CONTENT_1
                    ],
                ],
            ]
        ],

        'slide2' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'slide2',
            'elements' => [
                Slider::FIELD_SLIDE_NAME_2 => [
                    'type' => Text::TYPE_NAME,
                    'label' => Slider::FIELD_SLIDE_NAME_2,
                    'options' => [
                        'dataSource' => Slider::FIELD_SLIDE_NAME_2
                    ],
                ],
                Slider::FIELD_SLIDE_CONTENT_2 => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => Slider::FIELD_SLIDE_CONTENT_2,
                    'options' => [
                        'dataSource' => Slider::FIELD_SLIDE_CONTENT_2
                    ],
                ],
            ]
        ],

        'slide3' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'slide3',
            'elements' => [
                Slider::FIELD_SLIDE_NAME_3 => [
                    'type' => Text::TYPE_NAME,
                    'label' => Slider::FIELD_SLIDE_NAME_3,
                    'options' => [
                        'dataSource' => Slider::FIELD_SLIDE_NAME_3
                    ],
                ],
                Slider::FIELD_SLIDE_CONTENT_3 => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => Slider::FIELD_SLIDE_CONTENT_3,
                    'options' => [
                        'dataSource' => Slider::FIELD_SLIDE_CONTENT_3
                    ],
                ],
            ]
        ],

    ]
];