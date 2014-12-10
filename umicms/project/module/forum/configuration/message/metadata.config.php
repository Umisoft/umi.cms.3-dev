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
use umi\orm\metadata\IObjectType;
use umicms\filter\HtmlPurifier;
use umicms\project\module\forum\model\object\BaseForumMessage;
use umicms\project\module\forum\model\object\ForumBranchMessage;
use umicms\project\module\forum\model\object\ForumMessage;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/hierarchicCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/recyclable.config.php',
    [
        'dataSource' => [
            'sourceName' => 'forum_message'
        ],
        'fields' => [
            BaseForumMessage::FIELD_PUBLISH_TIME => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'publish_time',
            ],
            BaseForumMessage::FIELD_THEME => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'theme_id',
                'target' => 'forumTheme',
                'mutator' => 'setTheme'
            ],
            ForumMessage::FIELD_CONTENTS => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => [
                        'columnName' => 'contents',
                        'filters' => [
                            HtmlPurifier::TYPE => []
                        ]
                    ],
                    'en-US' => [
                        'columnName' => 'contents',
                        'filters' => [
                            HtmlPurifier::TYPE => []
                        ]
                    ]
                ]
            ],
            ForumMessage::FIELD_CONTENTS_RAW => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'contents_raw',
                'mutator' => 'setContents',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'contents_raw'],
                    'en-US' => ['columnName' => 'contents_raw_en']
                ]
            ],
            ForumMessage::FIELD_AUTHOR => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'author_id',
                'target' => 'forumAuthor',
                'mutator' => 'setAuthor'
            ],
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\forum\model\object\BaseForumMessage',
                'fields' => [
                    BaseForumMessage::FIELD_THEME => []
                ]
            ],
            ForumBranchMessage::TYPE_NAME => [
                'objectClass' => 'umicms\project\module\forum\model\object\ForumBranchMessage',
                'fields' => [
                    BaseForumMessage::FIELD_PUBLISH_TIME => [],
                    BaseForumMessage::FIELD_THEME => []
                ]
            ],
            ForumMessage::TYPE_NAME => [
                'objectClass' => 'umicms\project\module\forum\model\object\ForumMessage',
                'fields' => [
                    BaseForumMessage::FIELD_PUBLISH_TIME => [],
                    BaseForumMessage::FIELD_THEME => [],
                    ForumMessage::FIELD_CONTENTS => [],
                    ForumMessage::FIELD_CONTENTS_RAW => [],
                    ForumMessage::FIELD_AUTHOR => []
                ]
            ]
        ]
    ]
);
