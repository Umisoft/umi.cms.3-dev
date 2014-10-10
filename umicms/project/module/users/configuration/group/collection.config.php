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
use umicms\project\module\users\model\collection\UserGroupCollection;
use umicms\project\module\users\model\object\UserGroup;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\users\model\collection\UserGroupCollection',
    'handlers' => [
        'admin' => 'users.group'
    ],
    'forms' => [
        'base' => [
            UserGroupCollection::FORM_EDIT => '{#lazy:~/project/module/users/configuration/group/form/base.edit.config.php}',
            UserGroupCollection::FORM_CREATE => '{#lazy:~/project/module/users/configuration/group/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.userGroup' => 'collection.userGroup', 'collection' => 'collection'
    ],
    UserGroupCollection::IGNORED_TABLE_FILTER_FIELDS => [
        UserGroup::FIELD_ROLES => []
    ]
];