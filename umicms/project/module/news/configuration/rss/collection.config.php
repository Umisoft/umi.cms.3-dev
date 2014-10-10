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
use umicms\project\module\news\model\collection\NewsRssImportScenarioCollection;
use umicms\project\module\news\model\object\NewsRssImportScenario;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\news\model\collection\NewsRssImportScenarioCollection',
    'handlers' => [
        'admin' => 'news.rss',
        'site' => 'news.rss'
    ],
    'forms' => [
        'base' => [
            NewsRssImportScenarioCollection::FORM_EDIT => '{#lazy:~/project/module/news/configuration/rss/form/base.edit.config.php}',
            NewsRssImportScenarioCollection::FORM_CREATE => '{#lazy:~/project/module/news/configuration/rss/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.newsRssImportScenario' => 'collection.newsRssImportScenario', 'collection' => 'collection'
    ],

    NewsRssImportScenarioCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        NewsRssImportScenario::FIELD_RUBRIC => [],
        NewsRssImportScenario::FIELD_RSS_URL => []
    ]
];