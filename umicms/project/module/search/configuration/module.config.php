<?php

return [
    'name' => 'search',
    'api' => [
        'umicms\project\module\search\model\SearchIndexApi' => [],
        'umicms\project\module\search\model\SearchApi' => [
            'minimumPhraseLength' => 3
        ]
    ],
    'models' => [
        'index' => '~/project/module/search/configuration/index',
    ]
];