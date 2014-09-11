<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Types\Type;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/scheme/collection.config.php',
    [
        'name' => 'dispatch_unsubscribe_dis',
        'columns' => [
            'dispatch_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'subscriber_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ]
        ],
        'indexes' => [
            'dispatch' => [
                'columns' => [
                    'dispatch_id' => []
                ]
            ],
            'subscriber' => [
                'columns' => [
                    'subscriber_id' => []
                ]
            ]
        ],
        'constraints' => [
            'dis_to_unsubscribe' => [
                'foreignTable' => 'dispatch',
                'columns' => [
                    'dispatch_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'unsubscribe_to_dis' => [
                'foreignTable' => 'dispatch_subscriber',
                'columns' => [
                    'subscriber_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);