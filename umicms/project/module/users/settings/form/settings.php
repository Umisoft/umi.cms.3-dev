<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\form\element\Checkbox;
use umi\form\element\CheckboxGroup;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\users\api\collection\UserCollection;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.users'
        ]
    ],

    'elements' => [

        'Registration' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'Registration',
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
        ],
        'Notifications' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'Notifications',
            'elements' => [
                UserCollection::SETTING_MAIL_SENDER => [
                    'type' => Text::TYPE_NAME,
                    'label' => UserCollection::SETTING_MAIL_SENDER,
                    'options' => [
                        'dataSource' => UserCollection::SETTING_MAIL_SENDER
                    ]
                ],
                UserCollection::SETTING_MAIL_NOTIFICATION_RECIPIENTS => [
                    'type' => Text::TYPE_NAME,
                    'label' => UserCollection::SETTING_MAIL_NOTIFICATION_RECIPIENTS,
                    'options' => [
                        'dataSource' => UserCollection::SETTING_MAIL_NOTIFICATION_RECIPIENTS
                    ]
                ],
            ]
        ]
    ]
];