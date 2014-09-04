<?php

use umi\form\element\Checkbox;
use umi\form\element\MultiSelect;
use umicms\project\module\surveys\model\object\Survey;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.edit.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.survey' => 'collection.survey'
            ]
        ],

        'elements' => [
            'contents' => [
                'elements' => [
                    Survey::FIELD_ANSWERS => [
                        'type' => MultiSelect::TYPE_NAME,
                        'label' => Survey::FIELD_ANSWERS,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => Survey::FIELD_ANSWERS
                        ]
                    ],
                    Survey::FIELD_MULTIPLE_CHOICE => [
                        'type' => Checkbox::TYPE_NAME,
                        'label' => Survey::FIELD_MULTIPLE_CHOICE,
                        'options' => [
                            'dataSource' => Survey::FIELD_MULTIPLE_CHOICE
                        ]
                    ]
                ]
            ]
        ]
    ]
);