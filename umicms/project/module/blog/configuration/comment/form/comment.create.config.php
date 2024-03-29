<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\html5\DateTime;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\model\object\BlogComment;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogComment' => 'collection.blogComment', 'collection' => 'collection', 'form' => 'form'
        ]
    ],
    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BlogComment::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogComment::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                BlogComment::FIELD_POST => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogComment::FIELD_POST,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogComment::FIELD_POST
                    ]
                ],
                BlogComment::FIELD_AUTHOR => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogComment::FIELD_AUTHOR,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogComment::FIELD_AUTHOR
                    ],
                ],
                BlogComment::FIELD_PUBLISH_TIME => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => BlogComment::FIELD_PUBLISH_TIME,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_PUBLISH_TIME
                    ]
                ],
                BlogComment::FIELD_STATUS => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogComment::FIELD_STATUS,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogComment::FIELD_STATUS
                    ],
                ],
                BlogComment::FIELD_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => BlogComment::FIELD_CONTENTS,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_CONTENTS
                    ]
                ]
            ],

        ]
    ]
];