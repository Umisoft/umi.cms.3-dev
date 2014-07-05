<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\blog\model\object\BlogComment;

return [
        'en-US' => [
            BlogComment::FIELD_AUTHOR => 'Author',
            BlogComment::FIELD_PUBLISH_TIME => 'Publish time',
            BlogComment::FIELD_PUBLISH_STATUS => 'Publish status',
            'type:base:displayName' => 'Post comment',
            BlogComment::COMMENT_STATUS_NEED_MODERATE => 'Moderate',
            BlogComment::COMMENT_STATUS_REJECTED => 'Rejected',
            BlogComment::COMMENT_STATUS_PUBLISHED => 'Published'
        ],

        'ru-RU' => [
            BlogComment::FIELD_AUTHOR => 'Автор',
            BlogComment::FIELD_PUBLISH_TIME => 'Дата публикации',
            BlogComment::FIELD_PUBLISH_STATUS => 'Статус публикации',
            'type:base:displayName' => 'Комментарий поста',
            BlogComment::COMMENT_STATUS_NEED_MODERATE => 'На модерации',
            BlogComment::COMMENT_STATUS_REJECTED => 'Отклонён',
            BlogComment::COMMENT_STATUS_PUBLISHED => 'Опубликован'
        ]
    ];