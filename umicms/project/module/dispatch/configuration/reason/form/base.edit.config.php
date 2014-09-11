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
use umicms\project\module\dispatch\model\object\Reason;

return [

    'options' => [
        'dictionaries' => [
            'collection.Reason', 'collection', 'form'
        ]
    ],

    'elements' => [
        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Reason::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Reason::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Reason::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                Reason::FIELD_RELEASE => [
                    'type' => Select::TYPE_NAME,
                    'label' => Reason::FIELD_RELEASE,
                    'options' => [
                        'dataSource' => Reason::FIELD_RELEASE
                    ]
                ],
                Reason::FIELD_SUBSCRIBER => [
                    'type' => Select::TYPE_NAME,
                    'label' => Reason::FIELD_SUBSCRIBER,
                    'options' => [
                        'dataSource' => Reason::FIELD_SUBSCRIBER
                    ]
                ],
                Reason::FIELD_DATE_UNSUBSCIBE => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => Reason::FIELD_DATE_UNSUBSCIBE,
                    'options' => [
                        'dataSource' => Reason::FIELD_DATE_UNSUBSCIBE
                    ]
                ],
            ],
        ]
    ]
];