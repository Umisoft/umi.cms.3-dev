<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\filter\HtmlPurifier;
use umicms\project\module\forum\model\object\ForumAuthor;
use umicms\project\module\forum\model\object\ForumMessage;
use umicms\project\module\forum\model\object\ForumTheme;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/pageCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/userAssociated.config.php',
    [
        'dataSource' => [
            'sourceName' => 'forum_author'
        ],
        'fields' => [
            ForumAuthor::FIELD_THEMES => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'forumTheme',
                'targetField' => ForumTheme::FIELD_AUTHOR,
                'readOnly' => true
            ],
            ForumAuthor::FIELD_MESSAGES => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'blogComment',
                'targetField' => ForumMessage::FIELD_AUTHOR,
                'readOnly' => true
            ],
            ForumAuthor::FIELD_PAGE_CONTENTS => [
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => [
                        'filters' => [
                            HtmlPurifier::TYPE => []
                        ]
                    ],
                    'en-US' => [
                        'filters' => [
                            HtmlPurifier::TYPE => []
                        ]
                    ]
                ]
            ],
            ForumAuthor::FIELD_PAGE_CONTENTS_RAW => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents_raw',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents_raw'],
                    'en-US' => ['columnName' => 'contents_raw_en']
                ]
            ],
            ForumAuthor::FIELD_MESSAGES_COUNT => [
                'type' => IField::TYPE_DELAYED,
                'columnName' => 'message_count',
                'defaultValue' => 0,
                'dataType' => 'integer',
                'formula' => 'calculateMessagesCount',
                'readOnly' => true
            ],
            ForumAuthor::FIELD_THEMES_COUNT => [
                'type' => IField::TYPE_DELAYED,
                'columnName' => 'themes_count',
                'defaultValue' => 0,
                'dataType' => 'integer',
                'formula' => 'calculateThemesCount',
                'readOnly' => true
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\forum\model\object\ForumAuthor',
                'fields' => [
                    ForumAuthor::FIELD_PAGE_CONTENTS_RAW => [],
                    ForumAuthor::FIELD_THEMES => [],
                    ForumAuthor::FIELD_MESSAGES => [],
                    ForumAuthor::FIELD_MESSAGES_COUNT => [],
                    ForumAuthor::FIELD_THEMES_COUNT => []
                ]
            ]
        ]
    ]
);
