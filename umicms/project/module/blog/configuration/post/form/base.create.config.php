<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Checkbox;
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
                        'dataSource' => BlogPost::FIELD_PAGE_LAYOUT
                    ],
                ],
                BlogPost::FIELD_PAGE_SLUG => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogPost::FIELD_PAGE_SLUG,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_PAGE_SLUG
                    ],
                ],
                BlogPost::FIELD_ACTIVE => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => BlogPost::FIELD_ACTIVE,
                    'options' => [
                        'dataSource' => BlogPost::FIELD_ACTIVE
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