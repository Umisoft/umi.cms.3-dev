<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\html5\DateTime;
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umicms\form\element\Wysiwyg;
use umicms\project\module\news\model\object\NewsItem;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.edit.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.newsItem'
            ]
        ],

        'elements' => [
            'contents' => [
                'elements' => [
                    NewsItem::FIELD_RUBRIC => [
                        'type' => Select::TYPE_NAME,
                        'label' => NewsItem::FIELD_RUBRIC,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => NewsItem::FIELD_RUBRIC
                        ]
                    ],

                    NewsItem::FIELD_SUBJECTS => [
                        'type' => MultiSelect::TYPE_NAME,
                        'label' => NewsItem::FIELD_SUBJECTS,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => NewsItem::FIELD_SUBJECTS
                        ]
                    ],

                    NewsItem::FIELD_DATE => [
                        'type' => DateTime::TYPE_NAME,
                        'label' => NewsItem::FIELD_DATE,
                        'options' => [
                            'dataSource' => NewsItem::FIELD_DATE
                        ]
                    ],

                    NewsItem::FIELD_ANNOUNCEMENT => [
                        'type' => Wysiwyg::TYPE_NAME,
                        'label' => NewsItem::FIELD_ANNOUNCEMENT,
                        'options' => [
                            'dataSource' => NewsItem::FIELD_ANNOUNCEMENT
                        ]
                    ],

                    NewsItem::FIELD_SOURCE => [
                        'type' => Text::TYPE_NAME,
                        'label' => NewsItem::FIELD_SOURCE,
                        'options' => [
                            'dataSource' => NewsItem::FIELD_SOURCE
                        ]
                    ]
                ]
            ]
        ]
    ]
);