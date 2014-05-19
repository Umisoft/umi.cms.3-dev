<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\form\element\Checkbox;
use umi\form\element\CheckboxGroup;
use umi\form\element\Text;
use umicms\project\module\users\api\collection\UserCollection;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.settings.users'
        ]
    ],

    'elements' => [
        UserCollection::SETTING_REGISTRATION_WITH_ACTIVATION => [
            'type' => Checkbox::TYPE_NAME,
            'label' => UserCollection::SETTING_REGISTRATION_WITH_ACTIVATION,
            'options' => [
                'dataSource' => UserCollection::SETTING_REGISTRATION_WITH_ACTIVATION
            ]
        ],
        UserCollection::SETTING_MIN_PASSWORD_LENGTH => [
            'type' => Text::TYPE_NAME,
            'label' => UserCollection::SETTING_MIN_PASSWORD_LENGTH,
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_INT => []
                ],
                'dataSource' => UserCollection::SETTING_MIN_PASSWORD_LENGTH
            ]
        ],
        UserCollection::SETTING_FORBID_PASSWORD_LOGIN_EQUALITY => [
            'type' => Checkbox::TYPE_NAME,
            'label' => UserCollection::SETTING_FORBID_PASSWORD_LOGIN_EQUALITY,
            'options' => [
                'dataSource' => UserCollection::SETTING_FORBID_PASSWORD_LOGIN_EQUALITY
            ]
        ],
        UserCollection::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS => [
            'type' => CheckboxGroup::TYPE_NAME,
            'label' => UserCollection::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS,
            'options' => [
                //TODO специальное поле
                //'dataSource' => UserCollection::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS
            ]
        ],
    ]
];