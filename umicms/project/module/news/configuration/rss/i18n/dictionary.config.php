<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\news\model\object\NewsRssImportScenario;

return [
        'en-US' => [
            NewsRssImportScenario::FIELD_RSS_URL => 'RSS feed URL',
            NewsRssImportScenario::FIELD_SUBJECTS => 'Subjects',
            NewsRssImportScenario::FIELD_RUBRIC => 'Rubric',

            'type:base:displayName' => 'RSS import'
        ],

        'ru-RU' => [
            NewsRssImportScenario::FIELD_RSS_URL => 'URL RSS-ленты',
            NewsRssImportScenario::FIELD_SUBJECTS => 'Сюжеты',
            NewsRssImportScenario::FIELD_RUBRIC => 'Рубрика',

            'type:base:displayName' => 'Управление импортом RSS-лент'
        ]
    ];