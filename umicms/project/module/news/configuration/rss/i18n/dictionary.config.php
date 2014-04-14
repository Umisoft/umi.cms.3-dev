<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\news\api\object\RssImportItem;

return [
        'en-US' => [
            RssImportItem::FIELD_RSS_URL => 'URL RSS feed',
            RssImportItem::FIELD_SUBJECTS => 'Subjects',
            RssImportItem::FIELD_RUBRIC => 'Rubric'
        ],

        'ru-RU' => [
            RssImportItem::FIELD_RSS_URL => 'URL RSS-ленты',
            RssImportItem::FIELD_SUBJECTS => 'Сюжеты',
            RssImportItem::FIELD_RUBRIC => 'Рубрика'
        ]
    ];