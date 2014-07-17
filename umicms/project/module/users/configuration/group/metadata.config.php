<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\orm\metadata\field\SerializedArrayField;
use umicms\project\module\users\model\object\UserGroup;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/active.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/locked.config.php',
    [
        'dataSource' => [
            'sourceName' => 'users_group'
        ],
        'fields'     => [
            UserGroup::FIELD_USERS        => [
                'type'         => IField::TYPE_MANY_TO_MANY,
                'target'       => 'user',
                'bridge'       => 'userUserGroup',
                'relatedField' => 'userGroup',
                'targetField'  => 'user'
            ],
            UserGroup::FIELD_ROLES        => [
                'type' => SerializedArrayField::TYPE,
                'columnName' => 'roles',
                'accessor'   => 'getRoles',
                'mutator'    => 'setRoles'
            ]
        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\users\model\object\UserGroup',
                'fields'      => [
                    UserGroup::FIELD_USERS => [],
                    UserGroup::FIELD_ROLES => []
                ]
            ]
        ]
    ]
);
