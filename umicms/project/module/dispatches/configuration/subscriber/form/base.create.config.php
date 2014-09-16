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
use umicms\project\module\dispatches\model\object\BaseSubscriber;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\SubscriberUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.dispatchSubscriber', 'collection', 'form'
        ]
    ],

    'elements' => [
        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BaseSubscriber::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_DISPLAY_NAME
                    ],
                ],
                BaseSubscriber::FIELD_EMAIL => [
                    'type' => Text::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_EMAIL,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_EMAIL
                    ],
                ],
                BaseSubscriber::FIELD_FIRST_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_FIRST_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_FIRST_NAME
                    ],
                ],
                BaseSubscriber::FIELD_LAST_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_LAST_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_LAST_NAME
                    ],
                ],
                BaseSubscriber::FIELD_MIDDLE_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_MIDDLE_NAME,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_MIDDLE_NAME
                    ],
                ],
                BaseSubscriber::FIELD_SEX => [
                    'type' => Select::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_SEX,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_SEX
                    ],
                ],
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'additional',
            'elements' => [
                BaseSubscriber::FIELD_DISPATCH => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_DISPATCH,
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_DISPATCH,
                        'lazy' => true
                    ]
                ],
//				BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES => [
//                    'type' => MultiSelect::TYPE_NAME,
//                    'label' => BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES,
//                    'options' => [
//                        'dataSource' => BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES,
//                        'lazy' => true
//                    ]
//                ],
                BaseSubscriber::FIELD_PROFILE => [
                    'type' => Select::TYPE_NAME,
                    'label' => BaseSubscriber::FIELD_PROFILE,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BaseSubscriber::FIELD_PROFILE
                    ],
                ],
            ],

        ]
    ]
];