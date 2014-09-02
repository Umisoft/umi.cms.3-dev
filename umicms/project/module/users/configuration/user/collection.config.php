<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umi\orm\metadata\IObjectType;
use umicms\project\module\users\model\collection\UserCollection;
use umicms\project\module\users\model\object\Guest;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\object\Supervisor;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\users\model\collection\UserCollection',
    'handlers' => [
        'admin' => 'users.user'
    ],
    'forms' => [
        IObjectType::BASE => [
            UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/base.edit.config.php}'
        ],
        Guest::TYPE_NAME => [
            UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/guest.edit.config.php}'
        ],
        Supervisor::TYPE_NAME => [
            UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/registered.edit.config.php}',
            UserCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/user/form/registered.create.config.php}',
        ],
        RegisteredUser::TYPE_NAME => [
            UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/registered.edit.config.php}',
            UserCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/user/form/registered.create.config.php}',
            RegisteredUser::FORM_LOGIN_ADMIN => '{#lazy:~/project/module/users/configuration/user/form/login.config.php}',
            RegisteredUser::FORM_LOGIN_SITE => '{#lazy:~/project/module/users/site/authorization/form/login.config.php}',
            RegisteredUser::FORM_LOGOUT_SITE => '{#lazy:~/project/module/users/site/authorization/form/logout.config.php}',
            RegisteredUser::FORM_EDIT_PROFILE => '{#lazy:~/project/module/users/site/profile/form/profile.edit.config.php}',
            RegisteredUser::FORM_REGISTRATION => '{#lazy:~/project/module/users/site/registration/form/registration.config.php}',
            RegisteredUser::FORM_RESTORE_PASSWORD => '{#lazy:~/project/module/users/site/restoration/form/restore.password.config.php}',
            RegisteredUser::FORM_CHANGE_PASSWORD => '{#lazy:~/project/module/users/site/profile/password/form/change.password.config.php}',

            'supervisor' => [
                UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/registered.edit.config.php}',
                UserCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/user/form/registered.create.config.php}',
            ],
        ]
    ],
    'settings' => '{#lazy:~/project/module/users/configuration/user/collection.settings.config.php}',

    'dictionaries' => [
        'collection.user' => 'collection.user', 'collection' => 'collection'
    ],

    UserCollection::IGNORED_TABLE_FILTER_FIELDS => [
        RegisteredUser::FIELD_ACTIVATION_CODE => [],
        RegisteredUser::FIELD_PASSWORD_SALT => [],
        RegisteredUser::FIELD_PASSWORD => []
    ],

    UserCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        RegisteredUser::FIELD_LOGIN => [],
        RegisteredUser::FIELD_EMAIL => [],
        RegisteredUser::FIELD_REGISTRATION_DATE => []
    ]
];