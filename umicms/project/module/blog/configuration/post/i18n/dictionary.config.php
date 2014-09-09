<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\blog\model\object\BlogPost;
use umicms\project\module\blog\model\object\GuestBlogPost;

return [
        'en-US' => [
            'collection:blogPost:displayName' => 'Blog posts',

            BlogPost::FIELD_ANNOUNCEMENT => 'Announcement',
            BlogPost::FIELD_CATEGORY => 'Category',
            BlogPost::FIELD_TAGS => 'Tags',
            BlogPost::FIELD_AUTHOR => 'Author',
            GuestBlogPost::FIELD_AUTHOR => 'Author',
            BlogPost::FIELD_PUBLISH_TIME => 'Publish time',
            BlogPost::FIELD_SOURCE => 'Source',
            BlogPost::FIELD_STATUS => 'Status',
            BlogPost::FIELD_IMAGE => 'Image',

            'type:base:displayName' => 'Blog post',

        ],

        'ru-RU' => [
            'collection:blogPost:displayName' => 'Посты блога',

            BlogPost::FIELD_ANNOUNCEMENT => 'Анонс',
            BlogPost::FIELD_CATEGORY => 'Категория',
            BlogPost::FIELD_TAGS => 'Теги',
            BlogPost::FIELD_AUTHOR => 'Автор',
            GuestBlogPost::FIELD_AUTHOR => 'Автор',
            BlogPost::FIELD_PUBLISH_TIME => 'Дата публикации',
            BlogPost::FIELD_SOURCE => 'Источник публикации',
            BlogPost::FIELD_STATUS => 'Статус публикации',
            BlogPost::FIELD_IMAGE => 'Картинка',

            'type:base:displayName' => 'Пост блога',
        ]
    ];