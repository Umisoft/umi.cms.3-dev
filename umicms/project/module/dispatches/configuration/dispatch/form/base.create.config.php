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
use umicms\form\element\Wysiwyg;
use umicms\project\module\dispatches\model\object\Dispatch;

return [

    'options'  => [
        'dictionaries' => [
            'collection.dispatch',
            'collection',
            'form'
        ]
    ],
    'elements' => [
        'common'   => [
            'type'     => FieldSet::TYPE_NAME,
            'label'    => 'common',
            'elements' => [
                Dispatch::FIELD_DISPLAY_NAME => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Dispatch::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type'     => FieldSet::TYPE_NAME,
            'label'    => 'contents',
            'elements' => [
                Dispatch::FIELD_DESCRIPTION => [
                    'type'    => Wysiwyg::TYPE_NAME,
                    'label'   => Dispatch::FIELD_DESCRIPTION,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_DESCRIPTION
                    ]
                ],
                Dispatch::FIELD_SUBSCRIBERS => [
                    'type'    => MultiSelect::TYPE_NAME,
                    'label'   => Dispatch::FIELD_SUBSCRIBERS,
                    'options' => [
                        'dataSource' => Dispatch::FIELD_SUBSCRIBERS,
                        'lazy'       => true
                    ]
                ]
            ],
        ]
    ]
];