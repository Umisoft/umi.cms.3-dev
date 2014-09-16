<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\dispatches\model\object\Release;

return [

    'options'  => [
        'dictionaries' => [
            'collection.dispatchRelease',
            'collection',
            'form'
        ]
    ],
    'elements' => [
        'common'   => [
            'type'     => FieldSet::TYPE_NAME,
            'label'    => 'common',
            'elements' => [
                Release::FIELD_DISPLAY_NAME => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Release::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Release::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type'     => FieldSet::TYPE_NAME,
            'label'    => 'contents',
            'elements' => [
                Release::FIELD_DISPATCH => [
                    'type'    => Select::TYPE_NAME,
                    'label'   => Release::FIELD_DISPATCH,
                    'options' => [
                        'dataSource' => Release::FIELD_DISPATCH,
                        'lazy'       => true
                    ]
                ],
                Release::FIELD_SUBJECT  => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Release::FIELD_SUBJECT,
                    'options' => [
                        'dataSource' => Release::FIELD_SUBJECT
                    ]
                ],
                Release::FIELD_HEADER   => [
                    'type'    => Text::TYPE_NAME,
                    'label'   => Release::FIELD_HEADER,
                    'options' => [
                        'dataSource' => Release::FIELD_HEADER
                    ]
                ],
                Release::FIELD_MESSAGE  => [
                    'type'    => Wysiwyg::TYPE_NAME,
                    'label'   => Release::FIELD_MESSAGE,
                    'options' => [
                        'dataSource' => Release::FIELD_MESSAGE
                    ]
                ],
                Release::FIELD_TEMPLATE => [
                    'type'    => Select::TYPE_NAME,
                    'label'   => Release::FIELD_TEMPLATE,
                    'options' => [
                        'dataSource' => Release::FIELD_TEMPLATE,
                        'lazy'       => true
                    ]
                ]
            ],
        ]
    ]
];