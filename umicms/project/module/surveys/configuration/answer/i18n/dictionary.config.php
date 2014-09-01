<?php

use umicms\project\module\surveys\model\object\Answer;

return [
        'en-US' => [
            'collection:survey:displayName' => 'Answers',

            Answer::FIELD_SURVEY => 'Survey',

            'type:base:displayName' => 'Answer',
        ],

        'ru-RU' => [
            'collection:survey:displayName' => 'Ответы',

            Answer::FIELD_SURVEY => 'Опрос',
            
            'type:base:displayName' => 'Ответ'
        ]
    ];