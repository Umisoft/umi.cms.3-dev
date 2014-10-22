<?php

use umicms\project\module\surveys\model\object\Answer;

return [
        'en-US' => [
            'collection:survey:displayName' => 'Answers',

            Answer::FIELD_SURVEY => 'Survey',
            Answer::FIELD_VOTES => 'Number of votes',

            'type:base:displayName' => 'Answer',
        ],

        'ru-RU' => [
            'collection:survey:displayName' => 'Ответы',

            Answer::FIELD_SURVEY => 'Опрос',
            Answer::FIELD_VOTES => 'Количество голосов',

            'type:base:displayName' => 'Ответ'
        ]
    ];