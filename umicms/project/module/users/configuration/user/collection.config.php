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
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\users\model\object\RegisteredUser;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\users\model\collection\UserCollection',
    'handlers' => [
        'admin' => 'users.user'
    ],
    'forms' => [
        IObjectType::BASE => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/base.edit.config.php}'
        ],
        'guest' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/guest.edit.config.php}'
        ],
        'registered.supervisor' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/registered.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/user/form/registered.create.config.php}',
        ],
        RegisteredUser::TYPE_NAME => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/registered.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/user/form/registered.create.config.php}',
            RegisteredUser::FORM_LOGIN_ADMIN => '{#lazy:~/project/module/users/configuration/user/form/login.config.php}',
            RegisteredUser::FORM_LOGIN_SITE => '{#lazy:~/project/module/users/site/authorization/form/login.config.php}',
            RegisteredUser::FORM_LOGOUT_SITE => '{#lazy:~/project/module/users/site/authorization/form/logout.config.php}',
            RegisteredUser::FORM_EDIT_PROFILE => '{#lazy:~/project/module/users/site/profile/form/profile.edit.config.php}',
            RegisteredUser::FORM_REGISTRATION => '{#lazy:~/project/module/users/site/registration/form/registration.config.php}',
            RegisteredUser::FORM_RESTORE_PASSWORD => '{#lazy:~/project/module/users/site/restoration/form/restore.password.config.php}',
            RegisteredUser::FORM_CHANGE_PASSWORD => '{#lazy:~/project/module/users/site/profile/password/form/change.password.config.php}',
        ]
    ],
    'settings' => '{#lazy:~/project/module/users/configuration/user/collection.settings.config.php}',

    'dictionaries' => [
        'collection.user', 'collection'
    ]
];