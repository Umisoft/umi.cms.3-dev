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
        'name'    => 'dispatches_log_dispatch',
        'columns' => [
            'release_id' => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'subscriber_id' => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'read' => [
                'type'    => Type::BOOLEAN
            ],
            'sent' => [
                'type'    => Type::BOOLEAN
            ],
        ],
        'indexes'     => [
            'release'   => [
                'columns' => [
                    'release_id' => []
                ]
            ],
            'subscriber' => [
                'columns' => [
                    'subscriber_id' => []
                ]
            ]
        ],
        'constraints' => [
            'log_dispatch_to_release' => [
                'foreignTable' => 'dispatches_release',
                'columns'      => [
                    'release_id' => []
                ],
                'options'      => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            ],
            'log_dispatch_to_subscriber' => [
                'foreignTable' => 'dispatches_subscriber',
                'columns'      => [
                    'subscriber_id' => []
                ],
                'options'      => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            ]
        ]
    ]
);
