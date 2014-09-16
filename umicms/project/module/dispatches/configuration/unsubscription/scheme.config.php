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
        'name' => 'dispatches_unsubscribe_dis',
        'columns' => [
            'dispatch_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'unsubscriber_id' => [
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
            'unsubscriber' => [
                'columns' => [
                    'unsubscriber_id' => []
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
                'foreignTable' => 'dispatches_subscriber',
                'columns' => [
                    'unsubscriber_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);