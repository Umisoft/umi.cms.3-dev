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
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\api\object\BlogPost;

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
                BlogPost::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogPost::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_DISPLAY_NAME
                    ],
                ],
                BlogPost::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogPost::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogPost::FIELD_PAGE_LAYOUT
                    ],
                ]
            ]
        ],
        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                BlogPost::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogPost::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_PAGE_H1
                    ],
                ],
                BlogPost::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogPost::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_PAGE_META_TITLE
                    ],
                ],
                BlogPost::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogPost::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                BlogPost::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogPost::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                BlogPost::FIELD_CATEGORY => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogPost::FIELD_CATEGORY,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogPost::FIELD_CATEGORY
                    ]
                ],
                BlogPost::FIELD_TAGS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => BlogPost::FIELD_TAGS,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_TAGS
                    ]
                ],
                BlogPost::FIELD_AUTHOR => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogPost::FIELD_AUTHOR,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogPost::FIELD_AUTHOR
                    ],
                ],
                BlogPost::FIELD_PUBLISH_TIME => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => BlogPost::FIELD_PUBLISH_TIME,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_PUBLISH_TIME
                    ]
                ],
                BlogPost::FIELD_PUBLISH_STATUS => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogPost::FIELD_PUBLISH_STATUS,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_PUBLISH_STATUS,
                        'choices' => [
                            BlogPost::POST_STATUS_DRAFT => BlogPost::POST_STATUS_DRAFT,
                            BlogPost::POST_STATUS_NEED_MODERATE => BlogPost::POST_STATUS_NEED_MODERATE,
                            BlogPost::POST_STATUS_REJECTED => BlogPost::POST_STATUS_REJECTED,
                            BlogPost::POST_STATUS_PUBLISHED => BlogPost::POST_STATUS_PUBLISHED
                        ]
                    ]
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
                BlogPost::FIELD_SOURCE => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogPost::FIELD_SOURCE,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_SOURCE
                    ]
                ]
            ],

        ]
    ]
];