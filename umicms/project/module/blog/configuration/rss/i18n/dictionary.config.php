<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\blog\model\object\BlogRssImportScenario;

return [
        'en-US' => [
            BlogRssImportScenario::FIELD_RSS_URL => 'RSS feed URL',
            BlogRssImportScenario::FIELD_TAGS => 'Tags',
            BlogRssImportScenario::FIELD_CATEGORY => 'Category',
            'type:base:displayName' => 'RSS import'

        ],

        'ru-RU' => [
            BlogRssImportScenario::FIELD_RSS_URL => 'URL RSS-ленты',
            BlogRssImportScenario::FIELD_TAGS => 'Тэги',
            BlogRssImportScenario::FIELD_CATEGORY => 'Категория',
            'type:base:displayName' => 'Импорт RSS'
        ]
    ];