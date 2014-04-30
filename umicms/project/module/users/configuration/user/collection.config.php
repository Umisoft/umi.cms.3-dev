<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\users\api\object\AuthorizedUser;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\users\api\collection\UserCollection',
    'handlers' => [
        'admin' => 'users.user'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/base.edit.config.php}'
        ],
        'guest' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/guest.edit.config.php}'
        ],
        'authorized' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/user/form/authorized.edit.config.php}',
            AuthorizedUser::FORM_LOGIN_ADMIN => '{#lazy:~/project/module/users/configuration/user/form/authorized.login.config.php}',
            AuthorizedUser::FORM_LOGIN_SITE => '{#lazy:~/project/module/users/site/authorization/form/authorized.login.config.php}',
        ]
    ],
];