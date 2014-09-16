<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Checkbox;
use umi\form\element\html5\DateTime;
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\dispatches\model\object\Release;

return [

    'options' => [
        'dictionaries' => [
            'collection.dispatchRelease', 'collection', 'form'
        ]
    ],

    'elements' => [
        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Release::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Release::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                Release::FIELD_DISPATCHES => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_DISPATCHES,
                    'options' => [
                        'dataSource' => Release::FIELD_DISPATCHES
                    ]
                ],
                Release::FIELD_SUBJECT => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_SUBJECT,
                    'options' => [
                        'dataSource' => Release::FIELD_SUBJECT
                    ]
                ],
                Release::FIELD_MESSAGE_HEADER => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_MESSAGE_HEADER,
                    'options' => [
                        'dataSource' => Release::FIELD_MESSAGE_HEADER
                    ]
                ],
                Release::FIELD_MESSAGE => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => Release::FIELD_MESSAGE,
                    'options' => [
                        'dataSource' => Release::FIELD_MESSAGE
                    ]
                ],
                Release::FIELD_TEMPLATE_MESSAGE => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_TEMPLATE_MESSAGE,
                    'options' => [
                        'dataSource' => Release::FIELD_TEMPLATE_MESSAGE
                    ]
                ],
                Release::FIELD_SENDING_STATUS => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => Release::FIELD_SENDING_STATUS,
                    'options' => [
                        'dataSource' => Release::FIELD_SENDING_STATUS
                    ]
                ],
                Release::FIELD_COUNT_SEND_MESSAGE => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_COUNT_SEND_MESSAGE,
                    'options' => [
                        'dataSource' => Release::FIELD_COUNT_SEND_MESSAGE
                    ]
                ],
                Release::FIELD_COUNT_VIEWS => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_COUNT_VIEWS,
                    'options' => [
                        'dataSource' => Release::FIELD_COUNT_VIEWS
                    ]
                ],
                Release::FIELD_DATE_START => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => Release::FIELD_DATE_START,
                    'options' => [
                        'dataSource' => Release::FIELD_DATE_START
                    ]
                ],
                Release::FIELD_DATE_FINISH => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => Release::FIELD_DATE_FINISH,
                    'options' => [
                        'dataSource' => Release::FIELD_DATE_FINISH
                    ]
                ],
                /*Release::FIELD_PERCENT_READS => [
                    'type' => Text::TYPE_NAME,
                    'label' => Release::FIELD_PERCENT_READS,
                    'options' => [
                        'dataSource' => Release::FIELD_PERCENT_READS
                    ]
                ],*/
            ],
        ]
    ]
];