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
use umicms\project\module\users\model\collection\UserCollection;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.users'
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