<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\blog\api\object\BlogRssImportScenario;

return [
        'en-US' => [
            BlogRssImportScenario::FIELD_RSS_URL => 'URL RSS feed',
            BlogRssImportScenario::FIELD_TAGS => 'Tags',
            BlogRssImportScenario::FIELD_CATEGORY => 'Category'
        ],

        'ru-RU' => [
            BlogRssImportScenario::FIELD_RSS_URL => 'URL RSS-ленты',
            BlogRssImportScenario::FIELD_TAGS => 'Тэги',
            BlogRssImportScenario::FIELD_CATEGORY => 'Категория'
        ]
    ];