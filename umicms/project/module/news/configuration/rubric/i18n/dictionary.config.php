<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\news\api\object\NewsRubric;

return [
        'en-US' => [
            NewsRubric::FIELD_NEWS => 'News items',
           'type:base:displayName' => 'News rubric'
        ],

        'ru-RU' => [
            NewsRubric::FIELD_NEWS => 'Новости',
            'type:base:displayName' => 'Рубрика новостей',
            'type:base:createLabel' => 'Добавить рубрику'
        ]
    ];