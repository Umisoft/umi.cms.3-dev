<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\blog\model\object\BlogAuthor;

return [
        'en-US' => [
            'collection:blogAuthor:displayName' => 'Authors',

            BlogAuthor::FIELD_USER => 'User',
            'type:base:displayName' => 'Blog author'
        ],

        'ru-RU' => [
            'collection:blogAuthor:displayName' => 'Авторы',

            BlogAuthor::FIELD_USER => 'Пользователь',
            'type:base:displayName' => 'Автор блога'
        ]
    ];