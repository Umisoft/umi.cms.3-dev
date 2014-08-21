<?php

use umicms\project\module\surveys\model\object\Survey;

return [
        'en-US' => [
            'collection:survey:displayName' => 'Surveys',

            Survey::FIELD_ANSWERS => 'Answers',

            'type:base:displayName' => 'Survey'
        ],

        'ru-RU' => [
            'collection:survey:displayName' => 'Опросы',

            Survey::FIELD_ANSWERS => 'Ответы',

            'type:base:displayName' => 'Опрос'
        ]
    ];