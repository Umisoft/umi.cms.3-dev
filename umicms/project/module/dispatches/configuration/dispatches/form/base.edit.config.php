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
use umicms\project\module\dispatches\model\object\Dispatches;

return [
    'options' => [
        'dictionaries' => [
            'collection.dispatches', 'collection', 'form'
        ]
    ],

    'elements' => [
        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Dispatches::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Dispatches::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Dispatches::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                Dispatches::FIELD_DESCRIPTION => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => Dispatches::FIELD_DESCRIPTION,
                    'options' => [
                        'dataSource' => Dispatches::FIELD_DESCRIPTION
                    ]
                ],
				Dispatches::FIELD_DATE_LAST_SENDING => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => Dispatches::FIELD_DATE_LAST_SENDING,
                    'options' => [
                        'dataSource' => Dispatches::FIELD_DATE_LAST_SENDING
                    ]
                ],
				Dispatches::FIELD_SUBSCRIBERS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => Dispatches::FIELD_SUBSCRIBERS,
                    'options' => [
                        'dataSource' => Dispatches::FIELD_SUBSCRIBERS,
                        'lazy' => true
                    ]
                ],
            ],
        ]
    ]
];