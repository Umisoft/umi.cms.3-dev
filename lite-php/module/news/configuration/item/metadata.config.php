<?php

use umi\validation\IValidatorFactory;
use umicms\project\module\news\model\object\NewsItem;

return [
    'dataSource' => [
        'sourceName' => 'demo_news_item'
    ],
    'fields' => [
        NewsItem::FIELD_DISPLAY_NAME => [
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'display_name',
                    'validators' => [IValidatorFactory::TYPE_REQUIRED => []
                    ]
                ],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        NewsItem::FIELD_PAGE_CONTENTS => [
            'localizations' => [
                'ru-RU' => ['columnName' => 'contents'],
                'en-US' => ['columnName' => 'contents_en']
            ]
        ],
    ]
];