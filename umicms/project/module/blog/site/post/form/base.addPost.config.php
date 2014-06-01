<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Hidden;
use umi\form\element\MultiSelect;
use umi\form\element\Submit;
use umi\form\element\Text;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\module\blog\api\object\BlogTag;

return [
    'options' => [
        'dictionaries' => [
            'collection.blogPost', 'collection', 'form'
        ]
    ],
    'elements' => [
        BlogPost::FIELD_CATEGORY => [
            'type' => Hidden::TYPE_NAME,
            'label' => BlogPost::FIELD_CATEGORY,
            'options' => [
                'dataSource' => BlogPost::FIELD_CATEGORY
            ],
        ],
        BlogPost::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => BlogPost::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => BlogPost::FIELD_DISPLAY_NAME
            ],
        ],
        BlogPost::FIELD_PAGE_H1 => [
            'type' => Text::TYPE_NAME,
            'label' => BlogPost::FIELD_PAGE_H1,
            'options' => [
                'dataSource' => BlogPost::FIELD_PAGE_H1
            ],
        ],
        BlogPost::FIELD_PAGE_SLUG => [
            'type' => Text::TYPE_NAME,
            'label' => BlogPost::FIELD_PAGE_SLUG,
            'options' => [
                'dataSource' => BlogPost::FIELD_PAGE_SLUG
            ],
        ],
        BlogPost::FIELD_ANNOUNCEMENT => [
            'type' => Wysiwyg::TYPE_NAME,
            'label' => BlogPost::FIELD_ANNOUNCEMENT,
            'options' => [
                'dataSource' => BlogPost::FIELD_ANNOUNCEMENT
            ]
        ],
        BlogPost::FIELD_PAGE_CONTENTS => [
            'type' => Wysiwyg::TYPE_NAME,
            'label' => BlogPost::FIELD_PAGE_CONTENTS,
            'options' => [
                'dataSource' => BlogPost::FIELD_PAGE_CONTENTS
            ]
        ],
        BlogPost::FIELD_TAGS=> [
            'type' => MultiSelect::TYPE_NAME,
            'label' => BlogPost::FIELD_TAGS,
            'options' => [
                'dataSource' => BlogPost::FIELD_TAGS,
                'choicesSource' => [
                    'value' => BlogTag::FIELD_IDENTIFY,
                    'label' => BlogTag::FIELD_DISPLAY_NAME
                ]
            ]
        ],
        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Add'
        ]
    ]
];