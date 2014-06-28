<?php
use Doctrine\DBAL\Types\Type;

return [
        'name'        => 'demo_news_item',
        'columns'     =>  [
            'display_name_en' => [
                'type'    => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'announcement_en' => [
                'type' => Type::TEXT
            ],
            'contents_en'     => [
                'type' => Type::TEXT
            ],
         ]
    ];