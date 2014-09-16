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
use umicms\project\module\dispatches\model\object\Dispatch;

return [

    'options' => [
        'dictionaries' => [
            'collection.dispatch', 'collection', 'form'
        ]
    ],

    'elements' => [
        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Dispatch::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Dispatch::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                Dispatch::FIELD_DESCRIPTION => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => Dispatch::FIELD_DESCRIPTION,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_DESCRIPTION
                    ]
                ],
                Dispatch::FIELD_DATE_LAST_SENDING => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => Dispatch::FIELD_DATE_LAST_SENDING,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_DATE_LAST_SENDING
                    ]
                ],
                Dispatch::FIELD_GROUP_USER => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => Dispatch::FIELD_GROUP_USER,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_GROUP_USER,
                        'lazy' => true
                    ]
                ],
                Dispatch::FIELD_SUBSCRIBER => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => Dispatch::FIELD_SUBSCRIBER,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_SUBSCRIBER,
                        'lazy' => true
                    ]
                ],
                /*Dispatch::FIELD_UNSUBSCRIBER => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => Dispatch::FIELD_UNSUBSCRIBER,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_UNSUBSCRIBER,
                        'lazy' => true
                    ]
                ],*/
                Dispatch::FIELD_RELEASE => [
                    'type' => Select::TYPE_NAME,
                    'label' => Dispatch::FIELD_RELEASE,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_RELEASE,
                        'lazy' => true
                    ]
                ],
            ],
        ]
    ]
];