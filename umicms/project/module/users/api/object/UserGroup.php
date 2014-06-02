<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\api\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\CmsObject;

/**
 * Группа пользователей.
 *
 * @property IManyToManyObjectSet $users пользователи, входящие в группу
 * @property array $roles роли, доступные группе
 */
class UserGroup extends CmsObject implements IActiveAccessibleObject, ILockedAccessibleObject
{
    /**
     * Имя поля для хранения пользователей, входящих в группу
     */
    const FIELD_USERS = 'users';
    /**
     * Имя поля для хранения ролей группы
     */
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
