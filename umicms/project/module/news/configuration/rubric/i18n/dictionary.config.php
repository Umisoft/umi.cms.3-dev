<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\news\model\object\NewsRubric;

return [
        'en-US' => [
            'collection:newsRubric:displayName' => 'News rubrics',

            NewsRubric::FIELD_NEWS => 'News items'
        ],

        'ru-RU' => [
            'collection:newsRubric:displayName' => 'Новостные рубрики',

            NewsRubric::FIELD_NEWS => 'Новости'
        ]
    ];