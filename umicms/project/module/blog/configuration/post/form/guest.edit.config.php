<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\form\element\html5\DateTime;
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Image;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\model\object\GuestBlogPost;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogPost', 'collection', 'form'
        ]
    ],
    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                GuestBlogPost::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_DISPLAY_NAME
                    ],
                ],
                GuestBlogPost::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'choices' => [
                            null => 'Default or inherited layout'
                        ],
                        'lazy' => true,
                        'dataSource' => GuestBlogPost::FIELD_PAGE_LAYOUT
                    ],
                ]
            ]
        ],
        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                GuestBlogPost::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_PAGE_H1
                    ],
                ],
                GuestBlogPost::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_PAGE_META_TITLE
                    ],
                ],
                GuestBlogPost::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                GuestBlogPost::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                GuestBlogPost::FIELD_CATEGORY => [
                    'type' => Select::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_CATEGORY,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => GuestBlogPost::FIELD_CATEGORY
                    ]
                ],
                GuestBlogPost::FIELD_TAGS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_TAGS,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_TAGS,
                        'lazy' => true
                    ]
                ],
                GuestBlogPost::FIELD_AUTHOR => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_AUTHOR,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_AUTHOR
                    ]
                ],
                GuestBlogPost::FIELD_PUBLISH_TIME => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_PUBLISH_TIME,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_PUBLISH_TIME
                    ]
                ],
                GuestBlogPost::FIELD_STATUS => [
                    'type' => Select::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_STATUS,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => GuestBlogPost::FIELD_STATUS
                    ]
                ],
                GuestBlogPost::FIELD_ANNOUNCEMENT => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_ANNOUNCEMENT,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_ANNOUNCEMENT
                    ]
                ],
                GuestBlogPost::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_PAGE_CONTENTS_RAW
                    ]
                ],
                GuestBlogPost::FIELD_IMAGE . '_1' => [
                    'type' => Image::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_IMAGE,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_IMAGE
                    ]
                ],
                GuestBlogPost::FIELD_SOURCE => [
                    'type' => Text::TYPE_NAME,
                    'label' => GuestBlogPost::FIELD_SOURCE,
                    'options' => [
                        'dataSource' => GuestBlogPost::FIELD_SOURCE
                    ]
                ]
            ],

        ]
    ]
];