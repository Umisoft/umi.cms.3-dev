<?php

use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\surveys\model\object\Answer;

return [

    'options' => [
        'dictionaries' => [
            'collection.survey', 'collection', 'form'
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
                ]
            ]
        ]
    ]
];