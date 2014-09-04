<?php

use umi\form\element\CheckboxGroup;
use umi\form\fieldset\FieldSet;
use umicms\project\module\surveys\model\object\Survey;

return [
    'options' => [
        'dictionaries' => [
            'collection.survey', 'form', 'collection'
        ]
    ],

    'elements' => [
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                Survey::FIELD_ANSWERS => [
                    'type' => CheckboxGroup::TYPE_NAME,
                    'label' => Survey::FIELD_ANSWERS,
                    'options' => [
                        'dataSource' => Survey::FIELD_ANSWERS
                    ]
                ]
            ]
        ]
    ]
];