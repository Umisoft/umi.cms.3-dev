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
use umicms\project\module\blog\model\object\GuestBlogComment;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogComment', 'collection', 'form'
        ]
    ],
    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                GuestBlogComment::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogComment::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => GuestBlogComment::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                GuestBlogComment::FIELD_POST => [
                    'type' => Select::TYPE_NAME,
                    'label' => GuestBlogComment::FIELD_POST,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => GuestBlogComment::FIELD_POST
                    ]
                ],
                GuestBlogComment::FIELD_AUTHOR => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogComment::FIELD_AUTHOR,
                    'options' => [
                        'dataSource' => GuestBlogComment::FIELD_AUTHOR
                    ]
                ],
                GuestBlogComment::FIELD_PUBLISH_TIME => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => GuestBlogComment::FIELD_PUBLISH_TIME,
                    'options' => [
                        'dataSource' => GuestBlogComment::FIELD_PUBLISH_TIME
                    ]
                ],
                GuestBlogComment::FIELD_STATUS => [
                    'type' => Select::TYPE_NAME,
                    'label' => GuestBlogComment::FIELD_STATUS,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => GuestBlogComment::FIELD_STATUS
                    ],
                ],
                GuestBlogComment::FIELD_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => GuestBlogComment::FIELD_CONTENTS,
                    'options' => [
                        'dataSource' => GuestBlogComment::FIELD_CONTENTS_RAW
                    ]
                ]
            ],

        ]
    ]
];