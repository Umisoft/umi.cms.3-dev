<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\blog\api\object\BlogTag;

return [
        'en-US' => [
            BlogTag::FIELD_RSS => 'RSS feeds',
            'type:base:displayName' => 'Tag'
        ],

        'ru-RU' => [
            BlogTag::FIELD_RSS => 'RSS-ленты',
            'type:base:displayName' => 'Тэг'
        ]
    ];