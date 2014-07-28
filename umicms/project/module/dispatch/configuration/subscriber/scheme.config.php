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
        'name' => 'dispatch_subscriber',
        'columns'     =>  [
            'email' => [
                'type' => Type::STRING
            ],
            'token' => [
                'type' => Type::STRING
            ],
			'profile_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'first_name' => [
                'type' => Type::STRING
            ],
            'middle_name' => [
                'type' => Type::STRING
            ],
            'last_name' => [
                'type' => Type::STRING
            ],
            'sex_id'        => [
				'type'    => Type::BIGINT
			],
        ],
        'indexes' => [
			'profile' => [
                'columns' => [
                    'profile_id' => []
                ]
            ]
        ],
		'constraints' => [
            'profile_to_subscriber' => [
                'foreignTable' => 'users_user',
                'columns' => [
                    'profile_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'SET NULL'
                ]
            ]
        ]
    ]
);
