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
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\model\object\BlogComment;

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
                BlogComment::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogComment::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_DISPLAY_NAME
                    ],
                ],
                BlogComment::FIELD_ACTIVE => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => BlogComment::FIELD_ACTIVE,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_ACTIVE
                    ],
                ],
                BlogComment::FIELD_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogComment::FIELD_SLUG,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_SLUG
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
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
                BlogComment::FIELD_PUBLISH_STATUS => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogComment::FIELD_PUBLISH_STATUS,
                    'options' => [
                        'lazy' => false,
                        'dataSource' => BlogComment::FIELD_PUBLISH_STATUS,
                        'choices' => [
                            BlogComment::COMMENT_STATUS_NEED_MODERATE => BlogComment::COMMENT_STATUS_NEED_MODERATE,
                            BlogComment::COMMENT_STATUS_REJECTED => BlogComment::COMMENT_STATUS_REJECTED,
                            BlogComment::COMMENT_STATUS_PUBLISHED => BlogComment::COMMENT_STATUS_PUBLISHED
                        ]
                    ]
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