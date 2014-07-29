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
            BlogComment::FIELD_POST => 'Post',
            BlogComment::FIELD_PUBLISH_TIME => 'Publish time',
            BlogComment::FIELD_STATUS => 'Status',
            'type:base:displayName' => 'Post comment'
        ],

        'ru-RU' => [
            BlogComment::FIELD_AUTHOR => 'Автор',
            BlogComment::FIELD_POST => 'Пост',
            BlogComment::FIELD_PUBLISH_TIME => 'Дата публикации',
            BlogComment::FIELD_STATUS => 'Статус публикации',
            'type:base:displayName' => 'Комментарий поста'
        ]
    ];