<?php

return [
    'name' => 'news',
    'models' => [
        'newsItem' => '{#lazy:~/project/module/news/configuration/item/model.config.php}',
    ],
    'api' => [
        'apiInterface' => [
            'className' => 'apiClassName'
        ]
    ]
];