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
        'name' => 'users_user_auth_cookie',
        'columns' => [
            'user_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'token' => [
                'type'    => Type::GUID,
                'options' => [
                    'notnull' => true
                ]
            ]
        ],
        'indexes' => [
            'unique_guid_user'=> [
                'type' => 'unique',
                'columns' => [
                    'guid' => [],
                    'user_id' => []
                ]
            ],
            'user' => [
                'columns' => [
                    'user_id' => []
                ]
            ],
        ],
        'constraints' => [
            'to_user' => [
                'foreignTable' => 'users_user',
                'columns' => [
                    'user_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            ]
        ]
    ]
);