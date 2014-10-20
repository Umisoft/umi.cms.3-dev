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
use umicms\project\module\dispatches\model\object\Subscriber;

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
                Subscriber::FIELD_DISPLAY_NAME => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Subscriber::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Subscriber::FIELD_DISPLAY_NAME
                    ],
                ],
                Subscriber::FIELD_EMAIL        => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Subscriber::FIELD_EMAIL,
                    'options' => [
                        'dataSource' => Subscriber::FIELD_EMAIL
                    ],
                ],
                Subscriber::FIELD_FIRST_NAME   => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Subscriber::FIELD_FIRST_NAME,
                    'options' => [
                        'dataSource' => Subscriber::FIELD_FIRST_NAME
                    ],
                ],
                Subscriber::FIELD_LAST_NAME    => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Subscriber::FIELD_LAST_NAME,
                    'options' => [
                        'dataSource' => Subscriber::FIELD_LAST_NAME
                    ],
                ],
                Subscriber::FIELD_MIDDLE_NAME  => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Subscriber::FIELD_MIDDLE_NAME,
                    'options' => [
                        'dataSource' => Subscriber::FIELD_MIDDLE_NAME
                    ],
                ]
            ]
        ],
        'additional' => [
            'type'     => FieldSet::TYPE_NAME,
            'label'    => 'additional',
            'elements' => [
                Subscriber::FIELD_DISPATCHES => [
                    'type'    => MultiSelect::TYPE_NAME,
                    'label'   => Subscriber::FIELD_DISPATCHES,
                    'options' => [
                        'dataSource' => Subscriber::FIELD_DISPATCHES,
                        'lazy'       => true
                    ]
                ]
            ],

        ]
    ]
];