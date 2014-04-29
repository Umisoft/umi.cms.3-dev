<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\news\api\object\NewsItem;

return [
        'en-US' => [
            NewsItem::FIELD_SUBJECTS => 'Subjects',
            NewsItem::FIELD_ANNOUNCEMENT => 'Announcement',
            NewsItem::FIELD_DATE => 'Date',
            NewsItem::FIELD_RUBRIC => 'Rubric',

            'type:base:displayName' => 'News item'
        ],

        'ru-RU' => [
            NewsItem::FIELD_SUBJECTS => 'Сюжеты',
            NewsItem::FIELD_ANNOUNCEMENT => 'Анонс',
            NewsItem::FIELD_DATE => 'Дата',
            NewsItem::FIELD_RUBRIC => 'Рубрика',

            'type:base:displayName' => 'Новость'
        ]
    ];