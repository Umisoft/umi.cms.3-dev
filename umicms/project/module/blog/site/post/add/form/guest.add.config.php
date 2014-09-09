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
use umi\form\element\MultiSelect;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\model\object\GuestBlogPost;
use umicms\project\module\blog\model\object\BlogTag;

return [
    'options' => [
        'dictionaries' => [
            'collection.blogPost', 'collection', 'form'
        ]
    ],
    'attributes' => [
        'method' => 'post'
    ],
    'elements' => [
        GuestBlogPost::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => GuestBlogPost::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => GuestBlogPost::FIELD_DISPLAY_NAME
            ],
        ],
        GuestBlogPost::FIELD_PAGE_H1 => [
            'type' => Text::TYPE_NAME,
            'label' => GuestBlogPost::FIELD_PAGE_H1,
            'options' => [
                'dataSource' => GuestBlogPost::FIELD_PAGE_H1,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
        ],
        GuestBlogPost::FIELD_PAGE_SLUG => [
            'type' => Text::TYPE_NAME,
            'label' => GuestBlogPost::FIELD_PAGE_SLUG,
            'options' => [
                'dataSource' => GuestBlogPost::FIELD_PAGE_SLUG,
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
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
                'dataSource' => GuestBlogPost::FIELD_PAGE_CONTENTS
            ]
        ],
        GuestBlogPost::FIELD_TAGS=> [
            'type' => MultiSelect::TYPE_NAME,
            'label' => GuestBlogPost::FIELD_TAGS,
            'options' => [
                'dataSource' => GuestBlogPost::FIELD_TAGS,
                'choicesSource' => [
                    'value' => BlogTag::FIELD_IDENTIFY,
                    'label' => BlogTag::FIELD_DISPLAY_NAME
                ]
            ]
        ],
        GuestBlogPost::FIELD_AUTHOR => [
            'type' => Text::TYPE_NAME,
            'label' => GuestBlogPost::FIELD_AUTHOR,
            'options' => [
                'dataSource' => GuestBlogPost::FIELD_AUTHOR,
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
        ],
        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Add'
        ]
    ]
];