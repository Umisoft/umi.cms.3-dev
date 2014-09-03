<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;


return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/scheme/collection.config.php',
    [
        'name' => 'dispatch_release',
        'columns'     =>  [
            'dispatch' => [
                'type' => Type::STRING
            ],
            'subject' => [
                'type' => Type::STRING
            ],
            'message_header' => [
                'type' => Type::STRING
            ],
            'message' => [
                'type' => Type::TEXT
            ],
            'template_message' => [
                'type' => Type::STRING
            ],
            'sending_status' => [
                'type' => Type::STRING
            ],
            'date_start' => [
                'type' => Type::DATETIME
            ],
            'date_finish' => [
                'type' => Type::DATETIME
            ],
            'count_send_message' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'count_views' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'count_unsubscribe' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'percent_reads' => [
                'type' => Type::FLOAT
            ],
        ],
    ]
);
