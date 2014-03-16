<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;

/**
 * Группа пользователей.
 *
 * @property IManyToManyObjectSet $users пользователи, входящие в группу
 */
class Group extends CmsObject
{
    /**
     * Имя поля для хранения пользователей, входящих в группу
     */
    const FIELD_USERS = 'users';
}
