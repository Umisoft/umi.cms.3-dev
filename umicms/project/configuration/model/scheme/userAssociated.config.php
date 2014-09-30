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

/**
 * Схема колонок для коллекций, поддерживающих управлению активностью объекта на сайте.
 */
return [
    'columns' => [
        'user_id' => [
            'type' => Type::BIGINT,
            'options' => [
                'unsigned' => true
            ]
        ]
    ],
    'indexes' => [
        'user' => [
            'columns' => [
                'user_id' => []
            ]
        ],
    ],
    'constraints' => [
        'user_to_user' => [
            'foreignTable' => 'users_user',
            'columns' => [
                'user_id' => []
            ],
            'foreignColumns' => [
                'id' => []
            ],
            'options' => [
                'onUpdate' => 'CASCADE',
                'onDelete' => 'SET NULL'
            ]
        ]
    ]
];