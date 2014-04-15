<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\news\api\object\RssImportScenario;

return [
        'en-US' => [
            RssImportScenario::FIELD_RSS_URL => 'URL RSS feed',
            RssImportScenario::FIELD_SUBJECTS => 'Subjects',
            RssImportScenario::FIELD_RUBRIC => 'Rubric'
        ],

        'ru-RU' => [
            RssImportScenario::FIELD_RSS_URL => 'URL RSS-ленты',
            RssImportScenario::FIELD_SUBJECTS => 'Сюжеты',
            RssImportScenario::FIELD_RUBRIC => 'Рубрика'
        ]
    ];