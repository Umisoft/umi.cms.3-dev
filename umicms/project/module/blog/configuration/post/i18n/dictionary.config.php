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

return [
        'en-US' => [
            BlogPost::FIELD_ANNOUNCEMENT => 'Announcement',
            BlogPost::FIELD_CATEGORY => 'Category',
            BlogPost::FIELD_TAGS => 'Tags',
            BlogPost::FIELD_AUTHOR => 'Author',
            BlogPost::FIELD_PUBLISH_TIME => 'Publish time',
            BlogPost::FIELD_SOURCE => 'Source',
            'type:base:displayName' => 'Blog post',
            'publishStatus' => 'Publish status',
            BlogPost::POST_STATUS_DRAFT => 'Draft',
            BlogPost::POST_STATUS_NEED_MODERATE => 'Moderate',
            BlogPost::POST_STATUS_REJECTED => 'Rejected',
            BlogPost::POST_STATUS_PUBLISHED => 'Published'
        ],

        'ru-RU' => [
            BlogPost::FIELD_ANNOUNCEMENT => 'Анонс',
            BlogPost::FIELD_CATEGORY => 'Категория',
            BlogPost::FIELD_TAGS => 'Теги',
            BlogPost::FIELD_AUTHOR => 'Автор',
            BlogPost::FIELD_PUBLISH_TIME => 'Дата публикации',
            BlogPost::FIELD_SOURCE => 'Источник публикации',
            'type:base:displayName' => 'Пост блога',
            'publishStatus' => 'Статус публикации',
            BlogPost::POST_STATUS_DRAFT => 'Черновик',
            BlogPost::POST_STATUS_NEED_MODERATE => 'На модерации',
            BlogPost::POST_STATUS_REJECTED => 'Отклонен',
            BlogPost::POST_STATUS_PUBLISHED => 'Опубликован'
        ]
    ];