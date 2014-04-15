<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\blog\api\object\RssImportPost;

return [
        'en-US' => [
            RssImportPost::FIELD_RSS_URL => 'URL RSS feed',
            RssImportPost::FIELD_TAGS => 'Tags',
            RssImportPost::FIELD_CATEGORY => 'Category'
        ],

        'ru-RU' => [
            RssImportPost::FIELD_RSS_URL => 'URL RSS-ленты',
            RssImportPost::FIELD_TAGS => 'Тэги',
            RssImportPost::FIELD_CATEGORY => 'Категория'
        ]
    ];