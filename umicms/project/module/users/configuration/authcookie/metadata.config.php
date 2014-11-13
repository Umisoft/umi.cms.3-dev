<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\users\model\object\UserAuthCookie;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'users_user_auth_cookie'
        ],
        'fields' => [
            UserAuthCookie::FIELD_USER => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'user_id',
                'target' => 'user'
            ],
            UserAuthCookie::FIELD_TOKEN => [
                'type' => IField::TYPE_GUID,
                'columnName' => 'token',
                'accessor' => 'getToken',
                'mutator' => 'setToken'
            ]
        ],
        'types' => [
            'base' => [
                'objectClass' => '\umicms\project\module\users\model\object\UserAuthCookie',
                'fields' => [
                    UserAuthCookie::FIELD_USER => [],
                    UserAuthCookie::FIELD_TOKEN => []
                ]
            ]
        ]
    ]
);