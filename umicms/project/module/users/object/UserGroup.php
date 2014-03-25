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
 * @property array $roles роли, доступные группе
 */
class UserGroup extends CmsObject
{
    /**
     * Имя поля для хранения пользователей, входящих в группу
     */
    const FIELD_USERS = 'users';

    const FIELD_ROLES = 'roles';

    /**
     * Возвращает список ролей доступных группе по компонентам.
     * @return array
     */
    public function getRoles()
    {
        if ($value = $this->getProperty(self::FIELD_ROLES)->getValue()) {
            return unserialize($value);
        }

        return [];
    }

    /**
     * Устанавливает список ролей доступных группе по компонентам.
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->getProperty(self::FIELD_ROLES)->setValue(serialize($roles));

        return $this;
    }
}
