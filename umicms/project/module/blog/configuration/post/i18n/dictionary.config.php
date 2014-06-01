<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\blog\api\object\BlogPost;

return [
        'en-US' => [
            BlogPost::FIELD_ANNOUNCEMENT => 'Announcement',
            BlogPost::FIELD_CATEGORY => 'Category',
            BlogPost::FIELD_TAGS => 'Tags',
            BlogPost::FIELD_AUTHOR => 'Author',
            BlogPost::FIELD_PUBLISH_TIME => 'Publish time',
            BlogPost::FIELD_SOURCE => 'Source',
            'type:base:displayName' => 'Blog post'
        ],

        'ru-RU' => [
            BlogPost::FIELD_ANNOUNCEMENT => 'Анонс',
            BlogPost::FIELD_CATEGORY => 'Категория',
            BlogPost::FIELD_TAGS => 'Тэги',
            BlogPost::FIELD_AUTHOR => 'Автор',
            BlogPost::FIELD_PUBLISH_TIME => 'Дата публикации',
            BlogPost::FIELD_SOURCE => 'Источник публикации',
            'type:base:displayName' => 'Пост блога'
        ]
    ];