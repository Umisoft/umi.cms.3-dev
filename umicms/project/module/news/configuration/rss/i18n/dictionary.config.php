<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\news\api\object\NewsRssImportScenario;

return [
        'en-US' => [
            NewsRssImportScenario::FIELD_RSS_URL => 'URL RSS feed',
            NewsRssImportScenario::FIELD_SUBJECTS => 'Subjects',
            NewsRssImportScenario::FIELD_RUBRIC => 'Rubric'
        ],

        'ru-RU' => [
            NewsRssImportScenario::FIELD_RSS_URL => 'URL RSS-ленты',
            NewsRssImportScenario::FIELD_SUBJECTS => 'Сюжеты',
            NewsRssImportScenario::FIELD_RUBRIC => 'Рубрика'
        ]
    ];