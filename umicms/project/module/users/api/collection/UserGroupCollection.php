<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api\collection;

use umi\orm\metadata\IObjectType;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\users\api\object\UserGroup;

/**
 * Коллекция для работы с группами пользователями.
 *
 * @method CmsSelector|UserGroup[] select() Возвращает селектор для выбора групп пользователей.
 * @method UserGroup get($guid, $withLocalization = false)  Возвращает группу пользователей по GUID.
 * @method UserGroup getById($objectId, $withLocalization = false) Возвращает группу пользователей по id.
 * @method UserGroup add($typeName = IObjectType::BASE) Создает и возвращает группу пользователей.
 */
class UserGroupCollection extends SimpleCollection implements ILockedAccessibleCollection
{
    use TLockedAccessibleCollection;
}
 