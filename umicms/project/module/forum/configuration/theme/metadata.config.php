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
use umi\validation\IValidatorFactory;
use umicms\project\module\forum\model\object\ForumTheme;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/pageCollection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/recyclable.config.php',
    [
        'dataSource' => [
            'sourceName' => 'forum_theme',
        ],
        'fields' => [
            ForumTheme::FIELD_CONFERENCE => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'conference_id',
                'target' => 'forumConference',
                'mutator' => 'setConference',
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
            ForumTheme::FIELD_AUTHOR => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'author_id',
                'target' => 'forumAuthor',
                'mutator' => 'setAuthor'
            ],
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\forum\model\object\ForumTheme',
                'fields' => [
                    ForumTheme::FIELD_CONFERENCE => [],
                    ForumTheme::FIELD_AUTHOR => []
                ]
            ]
        ]
    ]
);