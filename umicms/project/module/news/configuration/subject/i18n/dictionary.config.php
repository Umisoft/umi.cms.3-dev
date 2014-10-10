<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\news\model\object\NewsSubject;

return [
        'en-US' => [
            'collection:newsSubject:displayName' => 'News subjects',

            NewsSubject::FIELD_NEWS => 'News items',

            'type:base:displayName' => 'News subject'
        ],

        'ru-RU' => [
            'collection:newsSubject:displayName' => 'Новостные сюжеты',

            NewsSubject::FIELD_NEWS => 'Новости',

            'type:base:displayName' => 'Сюжет'
        ]
    ];