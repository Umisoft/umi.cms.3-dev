<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Types\Type;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/scheme/collection.config.php',
    [
        'name'    => 'dispatches_release',
        'columns' => [
            'dispatch_id'           => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'subject'            => [
                'type' => Type::STRING
            ],
            'header'     => [
                'type' => Type::STRING
            ],
            'message'            => [
                'type' => Type::TEXT
            ],
            'template_id'   => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'status_id'     => [
                'type' => Type::BIGINT
            ],
            'start_time'         => [
                'type' => Type::DATETIME
            ],
            'finish_time'        => [
                'type' => Type::DATETIME
            ],
            'sent_message_count' => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'viewed_message_count'        => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'unsubscription_count'  => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'view_percent'      => [
                'type' => Type::FLOAT
            ],
        ],
    ]
);
