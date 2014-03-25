<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\project\module\service\api\object\Backup;

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