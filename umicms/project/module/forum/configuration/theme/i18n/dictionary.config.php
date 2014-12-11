<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\forum\model\object\ForumTheme;

return [
    'en-US' => [
        'collection:forumTheme:displayName' => 'Forum theme',

        ForumTheme::FIELD_CONFERENCE => 'Conference',
        ForumTheme::FIELD_AUTHOR => 'Author'
    ],
    'ru-RU' => [
        'collection:forumTheme:displayName' => 'Темы форума',

        ForumTheme::FIELD_CONFERENCE => 'Конференция',
        ForumTheme::FIELD_AUTHOR => 'Автор'
    ]
];