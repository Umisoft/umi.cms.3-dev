<?php

use umi\form\element\Checkbox;
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\surveys\model\object\Survey;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.create.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.Survey', 'form', 'collection'
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
                    ],
                    Survey::FIELD_PAGE_SLUG => [
                        'type' => Text::TYPE_NAME,
                        'label' => Survey::FIELD_PAGE_SLUG,
                        'options' => [
                            'dataSource' => Survey::FIELD_PAGE_SLUG
                        ],
                    ],
                    Survey::FIELD_ACTIVE => [
                        'type' => Checkbox::TYPE_NAME,
                        'label' => Survey::FIELD_ACTIVE,
                        'options' => [
                            'dataSource' => Survey::FIELD_ACTIVE
                        ],
                    ],
                    Survey::FIELD_ANSWERS => [
                        'type' => MultiSelect::TYPE_NAME,
                        'label' => Survey::FIELD_ANSWERS,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => Survey::FIELD_ANSWERS
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