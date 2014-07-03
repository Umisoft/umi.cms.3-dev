<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umicms\project\module\news\model\collection\NewsItemCollection;
use umicms\project\module\news\model\object\NewsItem;
use umicms\project\module\news\model\object\NewsRubric;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\news\model\collection\NewsItemCollection',
    'handlers' => [
        'admin' => 'news.item',
        'site' => 'news.item'
    ],
    'forms' => [
        'base' => [
            NewsItemCollection::FORM_EDIT => '{#lazy:~/project/module/news/configuration/item/form/base.edit.config.php}',
            NewsItemCollection::FORM_CREATE => '{#lazy:~/project/module/news/configuration/item/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.newsItem', 'collection'
    ],

    NewsItemCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        NewsItem::FIELD_RUBRIC . '.' . NewsRubric::FIELD_DISPLAY_NAME => [],
        NewsItem::FIELD_DATE => []
    ]
];