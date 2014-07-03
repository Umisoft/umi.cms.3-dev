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
use umicms\project\module\blog\model\collection\BlogRssImportScenarioCollection;
use umicms\project\module\blog\model\object\BlogCategory;
use umicms\project\module\blog\model\object\BlogRssImportScenario;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\blog\model\collection\BlogRssImportScenarioCollection',
    'handlers' => [
        'admin' => 'blog.rss',
        'site' => 'blog.rss'
    ],
    'forms' => [
        'base' => [
            BlogRssImportScenarioCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/rss/form/base.edit.config.php}',
            BlogRssImportScenarioCollection::FORM_CREATE => '{#lazy:~/project/module/blog/configuration/rss/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.blogRssImportScenario', 'collection'
    ],

    BlogRssImportScenarioCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        BlogRssImportScenario::FIELD_CATEGORY . '.' . BlogCategory::FIELD_DISPLAY_NAME => [],
        BlogRssImportScenario::FIELD_RSS_URL => []
    ]
];