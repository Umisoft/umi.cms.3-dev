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
use umicms\project\module\news\model\object\NewsItem;
use umicms\project\module\news\model\object\NewsRubric;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/hierarchicPageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'news_rubric'
        ],
        'fields' => [
            NewsRubric::FIELD_NEWS                  => [
                'type'        => IField::TYPE_HAS_MANY,
                'target'      => 'newsItem',
                'targetField' => NewsItem::FIELD_RUBRIC
            ],
        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\news\model\object\NewsRubric',
                'fields'      => [
                    NewsRubric::FIELD_NEWS => []
                 ]
            ]
        ]
    ]
);
