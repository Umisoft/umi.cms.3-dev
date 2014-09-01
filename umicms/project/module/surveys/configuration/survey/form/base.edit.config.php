<?php

use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\surveys\model\object\Survey;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.edit.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.survey', 'form', 'collection'
            ]
        ],

        'elements' => [

            'common' => [
                'type' => FieldSet::TYPE_NAME,
                'label' => 'common',
                'elements' => [
                    Survey::FIELD_DISPLAY_NAME => [
                        'type' => Text::TYPE_NAME,
                        'label' => Survey::FIELD_DISPLAY_NAME,
                        'options' => [
                            'dataSource' => Survey::FIELD_DISPLAY_NAME
                        ],
                    ],
                    Survey::FIELD_PAGE_LAYOUT => [
                        'type' => Select::TYPE_NAME,
                        'label' => Survey::FIELD_PAGE_LAYOUT,
                        'options' => [
                            'choices' => [
                                null => 'Default or inherited layout'
                            ],
                            'lazy' => true,
                            'dataSource' => Survey::FIELD_PAGE_LAYOUT
                        ],
                    ]
                ]
            ],

            'meta' => [
                'type' => FieldSet::TYPE_NAME,
                'label' => 'meta',
                'elements' => [
                    Survey::FIELD_PAGE_H1 => [
                        'type' => Text::TYPE_NAME,
                        'label' => Survey::FIELD_PAGE_H1,
                        'options' => [
                            'dataSource' => Survey::FIELD_PAGE_H1
                        ],
                    ],
                    Survey::FIELD_PAGE_META_TITLE => [
                        'type' => Text::TYPE_NAME,
                        'label' => Survey::FIELD_PAGE_META_TITLE,
                        'options' => [
                            'dataSource' => Survey::FIELD_PAGE_META_TITLE
                        ],
                    ],
                    Survey::FIELD_PAGE_META_KEYWORDS => [
                        'type' => Text::TYPE_NAME,
                        'label' => Survey::FIELD_PAGE_META_KEYWORDS,
                        'options' => [
                            'dataSource' => Survey::FIELD_PAGE_META_KEYWORDS
                        ]
                    ],
                    Survey::FIELD_PAGE_META_DESCRIPTION => [
                        'type' => Text::TYPE_NAME,
                        'label' => Survey::FIELD_PAGE_META_DESCRIPTION,
                        'options' => [
                            'dataSource' => Survey::FIELD_PAGE_META_DESCRIPTION
                        ]
                    ]
                ]
            ],

            'contents' => [
                'type' => FieldSet::TYPE_NAME,
                'label' => 'contents',
                'elements' => [

                    Survey::FIELD_PAGE_CONTENTS => [
                        'type' => Wysiwyg::TYPE_NAME,
                        'label' => Survey::FIELD_PAGE_CONTENTS,
                        'options' => [
                            'dataSource' => Survey::FIELD_PAGE_CONTENTS
                        ]
                    ]
                ]
            ]
        ]
    ]
);