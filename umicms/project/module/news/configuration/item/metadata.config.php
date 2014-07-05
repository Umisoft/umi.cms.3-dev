<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\Environment;
use umicms\project\module\news\model\object\NewsItem;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/pageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'news_item'
        ],
        'fields' => [
            NewsItem::FIELD_RUBRIC       => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'rubric_id',
                'target'     => 'newsRubric'
            ],
            NewsItem::FIELD_ANNOUNCEMENT => [
                'type'          => IField::TYPE_TEXT,
                'columnName'    => 'announcement',
                'localizations' => [
                    'ru-RU' => ['columnName' => 'announcement'],
                    'en-US' => ['columnName' => 'announcement_en']
                ]
            ],
            NewsItem::FIELD_SOURCE       => [
                'type'       => IField::TYPE_TEXT,
                'columnName' => 'source'
            ],
            NewsItem::FIELD_SUBJECTS     => [
                'type'         => IField::TYPE_MANY_TO_MANY,
                'target'       => 'newsSubject',
                'bridge'       => 'newsItemSubject',
                'relatedField' => 'newsItem',
                'targetField'  => 'subject'
            ],
            NewsItem::FIELD_DATE         => [
                'type'       => IField::TYPE_DATE_TIME,
                'columnName' => 'date'
            ]
        ],
        'types'  => [
            'base' => [
                'objectClass' => 'umicms\project\module\news\model\object\NewsItem',
                'fields'      => [
                    NewsItem::FIELD_RUBRIC => [],
                    NewsItem::FIELD_ANNOUNCEMENT => [],
                    NewsItem::FIELD_SOURCE => [],
                    NewsItem::FIELD_SUBJECTS => [],
                    NewsItem::FIELD_DATE => []
                ]
            ]
        ],
    ]
);
