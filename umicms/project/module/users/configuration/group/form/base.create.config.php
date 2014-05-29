<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\MultiSelect;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Permissions;
use umicms\project\module\users\api\object\UserGroup;

return [

    'options' => [
        'dictionaries' => [
            'collection.userGroup', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                UserGroup::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => UserGroup::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => UserGroup::FIELD_DISPLAY_NAME
                    ]
                ],
                UserGroup::FIELD_USERS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => UserGroup::FIELD_USERS,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => UserGroup::FIELD_USERS
                    ]
                ],
            ]

        ],
        'permissions' => [
            'type' => FieldSet::TYPE_NAME,
            'label'=> 'permissions',
            'elements' => [
                UserGroup::FIELD_ROLES => [
                    'type' => Permissions::TYPE_NAME,
                    'label' => UserGroup::FIELD_ROLES,
                    'options' => [
                        'dataSource' => UserGroup::FIELD_ROLES
                    ]
                ]
            ]
        ]
    ]
];