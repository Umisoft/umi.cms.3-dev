<?php

use umicms\project\module\surveys\model\object\Survey;

return [
        'en-US' => [
            'collection:survey:displayName' => 'Surveys',

            Survey::FIELD_ANSWERS => 'Answers',
            Survey::FIELD_MULTIPLE_CHOICE => 'Ability to choose multiple options',

            'type:base:displayName' => 'Survey'
        ],

        'ru-RU' => [
            'collection:survey:displayName' => 'Опросы',

            Survey::FIELD_ANSWERS => 'Ответы',
            Survey::FIELD_MULTIPLE_CHOICE => 'Возможность выбора нескольких вариантов ответа',

            'type:base:displayName' => 'Опрос'
        ]
    ];