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
use umicms\project\module\forum\model\object\BaseForumTheme;
use umicms\project\module\forum\model\object\ForumBranchTheme;
use umicms\project\module\forum\model\object\ForumTheme;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/hierarchicCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/recyclable.config.php',
    [
        'dataSource' => [
            'sourceName' => 'forum_theme',
        ],
        'fields' => [
            BaseForumTheme::FIELD_CONFERENCE => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'conference_id',
                'target' => 'forumConference',
                'mutator' => 'setConference'
            ]
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\forum\model\object\BaseForumTheme',
                'fields' => [
                    BaseForumTheme::FIELD_CONFERENCE => []
                ]
            ],
            ForumBranchTheme::TYPE_NAME => [
                'objectClass' => 'umicms\project\module\forum\model\object\ForumBranchTheme',
                'fields' => [

                ]
            ],
            ForumTheme::TYPE_NAME => [
                'objectClass' => 'umicms\project\module\forum\model\object\ForumTheme',
                'fields' => [

                ]
            ]
        ]
    ]
);