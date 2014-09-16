<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\MultiSelect;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\dispatches\model\object\BaseSubscriber;

return [

    'options'  => [
        'dictionaries' => [
            'collection.dispatchSubscriber',
            'collection',
            'form'
        ]
    ],
    'elements' => [
        'common'     => [
            'type'     => FieldSet::TYPE_NAME,
            'label'    => 'common',
            'elements' => [
                BaseSubscriber::FIELD_DISPLAY_NAME => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => BaseSubscriber::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_DISPLAY_NAME
                    ],
                ],
                BaseSubscriber::FIELD_EMAIL        => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => BaseSubscriber::FIELD_EMAIL,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_EMAIL
                    ],
                ],
                BaseSubscriber::FIELD_FIRST_NAME   => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => BaseSubscriber::FIELD_FIRST_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_FIRST_NAME
                    ],
                ],
                BaseSubscriber::FIELD_LAST_NAME    => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => BaseSubscriber::FIELD_LAST_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_LAST_NAME
                    ],
                ],
                BaseSubscriber::FIELD_MIDDLE_NAME  => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => BaseSubscriber::FIELD_MIDDLE_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_MIDDLE_NAME
                    ],
                ]
            ]
        ],
        'additional' => [
            'type'     => FieldSet::TYPE_NAME,
            'label'    => 'additional',
            'elements' => [
                BaseSubscriber::FIELD_DISPATCHES => [
                    'type'    => MultiSelect::TYPE_NAME,
                    'label'   => BaseSubscriber::FIELD_DISPATCHES,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_DISPATCHES,
                        'lazy'       => true
                    ]
                ]
            ],

        ]
    ]
];