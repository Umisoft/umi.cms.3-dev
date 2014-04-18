<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\blog\api\object\BlogPost;

return [
        'en-US' => [
            BlogPost::FIELD_CATEGORY => 'Category',
            BlogPost::FIELD_TAGS => 'Tags',
            BlogPost::FIELD_AUTHOR => 'Author',
            BlogPost::FIELD_PUBLISH_TIME => 'Publish time',
            BlogPost::FIELD_SOURCE => 'Source'
        ],

        'ru-RU' => [
            BlogPost::FIELD_CATEGORY => 'Категория',
            BlogPost::FIELD_TAGS => 'Тэги',
            BlogPost::FIELD_AUTHOR => 'Автор',
            BlogPost::FIELD_PUBLISH_TIME => 'Дата публикации',
            BlogPost::FIELD_SOURCE => 'Источник публикации'
        ]
    ];