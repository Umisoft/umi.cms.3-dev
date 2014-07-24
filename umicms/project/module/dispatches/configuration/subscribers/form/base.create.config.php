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
use umicms\project\module\dispatches\model\object\Subscribers;

return [

    'options' => [
        'dictionaries' => [
            'collection.subscribers', 'collection', 'form'
        ]
    ],

    'elements' => [
        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Subscribers::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Subscribers::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_DISPLAY_NAME
                    ],
                ],
				Subscribers::FIELD_EMAIL => [
                    'type' => Text::TYPE_NAME,
                    'label' => Subscribers::FIELD_EMAIL,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_EMAIL
                    ],
                ],
				Subscribers::FIELD_FIRST_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Subscribers::FIELD_FIRST_NAME,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_FIRST_NAME
                    ],
                ],
				Subscribers::FIELD_LAST_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Subscribers::FIELD_LAST_NAME,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_LAST_NAME
                    ],
                ],
				Subscribers::FIELD_MIDDLE_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Subscribers::FIELD_MIDDLE_NAME,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_MIDDLE_NAME
                    ],
                ],
				Subscribers::FIELD_SEX => [
                    'type' => Select::TYPE_NAME,
                    'label' => Subscribers::FIELD_SEX,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_SEX
                    ],
                ],
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
				Subscribers::FIELD_DISPATCHES => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => Subscribers::FIELD_DISPATCHES,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_DISPATCHES,
                        'lazy' => true
                    ]
                ],
				Subscribers::FIELD_UNSUBSCRIBE_DISPATCHES => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => Subscribers::FIELD_UNSUBSCRIBE_DISPATCHES,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_UNSUBSCRIBE_DISPATCHES,
                        'lazy' => true
                    ]
                ],
                /* Subscribers::FIELD_DESCRIPTION => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => Subscribers::FIELD_DESCRIPTION,
                    'options' => [
                        'dataSource' => Subscribers::FIELD_DESCRIPTION
                    ]
                ]  */
            ],

        ]
    ]
];