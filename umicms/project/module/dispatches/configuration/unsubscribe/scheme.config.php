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
            'dispatches_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'subscribers_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ]
        ],
        'indexes' => [
            'dispatches' => [
                'columns' => [
                    'dispatches_id' => []
                ]
            ],
            'subscribers' => [
                'columns' => [
                    'subscribers_id' => []
                ]
            ]
        ],
        'constraints' => [
            'dis_to_subscribe' => [
                'foreignTable' => 'dispatches',
                'columns' => [
                    'dispatches_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ],
            'subscribe_to_dis' => [
                'foreignTable' => 'dispatches_subscribers',
                'columns' => [
                    'subscribers_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);