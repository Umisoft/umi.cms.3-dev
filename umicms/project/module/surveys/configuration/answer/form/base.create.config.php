<?php

use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\surveys\model\object\Answer;

return [
    'options' => [
        'dictionaries' => [
            'collection.answer' => 'collection.answer', 'collection' => 'collection', 'form' => 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Answer::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Answer::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Answer::FIELD_DISPLAY_NAME
                    ],
                ],
                Answer::FIELD_SURVEY => [
                    'type' => Select::TYPE_NAME,
                    'label' => Answer::FIELD_SURVEY,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => Answer::FIELD_SURVEY
                    ]
                ]
            ]
        ]
    ]
];