<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\news\model\object\NewsItem;

return [
        'en-US' => [
            NewsItem::FIELD_SUBJECTS => 'Subjects',
            NewsItem::FIELD_ANNOUNCEMENT => 'Announcement',
            NewsItem::FIELD_DATE => 'Date',
            NewsItem::FIELD_RUBRIC => 'Rubric',
            NewsItem::FIELD_SOURCE => 'Source',

            'type:base:displayName' => 'News item'
        ],

        'ru-RU' => [
            NewsItem::FIELD_SUBJECTS => 'Сюжеты',
            NewsItem::FIELD_ANNOUNCEMENT => 'Анонс',
            NewsItem::FIELD_DATE => 'Дата',
            NewsItem::FIELD_RUBRIC => 'Рубрика',
            NewsItem::FIELD_SOURCE => 'Источник',

            'type:base:displayName' => 'Новость'
        ]
    ];