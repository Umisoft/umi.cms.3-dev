<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\service\model\object\Backup;

return [
        'en-US' => [
            Backup::FIELD_DATE => 'Date create backup',
            Backup::FIELD_DATA => 'Backup'
        ],

        'ru-RU' => [
            Backup::FIELD_DATE => 'Дата и время создания резервной копии',
            Backup::FIELD_DATA => 'Резервная копия'
        ]
    ];