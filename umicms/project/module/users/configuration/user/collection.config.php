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
use umicms\project\module\users\model\object\AuthorizedUser;

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
        'guest' => [
            UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/guest.edit.config.php}'
        ],
        'authorized.supervisor' => [
            UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/authorized.edit.config.php}',
            UserCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/user/form/authorized.create.config.php}',
        ],
        AuthorizedUser::TYPE_NAME => [
            UserCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/authorized.edit.config.php}',
            UserCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/user/form/authorized.create.config.php}',
            AuthorizedUser::FORM_LOGIN_ADMIN => '{#lazy:~/project/module/users/configuration/user/form/authorized.login.config.php}',
            AuthorizedUser::FORM_LOGIN_SITE => '{#lazy:~/project/module/users/site/authorization/form/authorized.login.config.php}',
            AuthorizedUser::FORM_LOGOUT_SITE => '{#lazy:~/project/module/users/site/authorization/form/authorized.logout.config.php}',
            AuthorizedUser::FORM_EDIT_PROFILE => '{#lazy:~/project/module/users/site/profile/form/authorized.profile.edit.config.php}',
            AuthorizedUser::FORM_REGISTRATION => '{#lazy:~/project/module/users/site/registration/form/authorized.registration.config.php}',
            AuthorizedUser::FORM_RESTORE_PASSWORD => '{#lazy:~/project/module/users/site/restoration/form/authorized.restore.password.config.php}',
            AuthorizedUser::FORM_CHANGE_PASSWORD => '{#lazy:~/project/module/users/site/profile/password/form/authorized.change.password.config.php}',
        ]
    ],
    'settings' => '{#lazy:~/project/module/users/configuration/user/collection.settings.config.php}',

    'dictionaries' => [
        'collection.user', 'collection'
    ],

    UserCollection::IGNORED_TABLE_FILTER_FIELDS => [
        AuthorizedUser::FIELD_ACTIVATION_CODE => [],
        AuthorizedUser::FIELD_PASSWORD_SALT => [],
        AuthorizedUser::FIELD_PASSWORD => []
    ],

    UserCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        AuthorizedUser::FIELD_LOGIN => [],
        AuthorizedUser::FIELD_EMAIL => [],
        AuthorizedUser::FIELD_REGISTRATION_DATE => []
    ]
];