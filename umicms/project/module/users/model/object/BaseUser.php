<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model\object;

use umi\hmvc\acl\IComponentRoleResolver;
use umi\hmvc\component\IComponent;
use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\CmsObject;

/**
 * Базовый класс пользователя.
 *
 * @property IManyToManyObjectSet $groups группы, в которые входит пользователь
 */
abstract class BaseUser extends CmsObject
    implements IComponentRoleResolver, IActiveAccessibleObject, ILockedAccessibleObject
{
    /**
     * @var array $roles список ролей пользователя по компонентам.
     */
    private $roles;

    /**
     * Имя поля для хранения групп, в которые входит пользователь
     */
    const FIELD_GROUPS = 'groups';

    /**
     * {@inheritdoc}
     */
    public function getRoleNames(IComponent $component)
    {
        $roles = $this->getAllRoles();

        return isset($roles[$component->getPath()]) ? $roles[$component->getPath()] : [];
    }

    /**
     * Проверяет разрешение на доступ к ресурсу компонента.
     * @param IComponent $component компонент
     * @param string $resourceName имя ресурса
     * @return bool
     */
    public function isAllowed(IComponent $component, $resourceName)
    {
        $roleNames = $this->getRoleNames($component);
        $aclManager = $component->getAclManager();
        foreach ($roleNames as $roleName) {
            if ($aclManager->isAllowed($roleName, $resourceName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Возвращает список всех ролей пользователя по компонентам.
     * @return array
     */
    protected function getAllRoles()
    {
        if (is_null($this->roles)) {
            $roles = [];
            /**
             * @var UserGroup $group
             */
            foreach ($this->groups as $group) {
                $roles = array_merge_recursive($roles, $group->roles);
            }

            $this->roles = $roles;
        }

        return $this->roles;
    }
}
 