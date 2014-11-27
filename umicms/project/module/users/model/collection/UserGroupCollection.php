<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\users\model\object\UserGroup;

/**
 * Коллекция для работы с группами пользователями.
 *
 * @method CmsSelector|UserGroup[] select() Возвращает селектор для выбора групп пользователей.
 * @method UserGroup get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает группу пользователей по GUID.
 * @method UserGroup getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает группу пользователей по id.
 * @method UserGroup add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает группу пользователей.
 */
class UserGroupCollection extends CmsCollection implements ILockedAccessibleCollection
{
    /**
     * Guid для системной группы "Зарегистрированные пользователи"
     */
    const REGISTERED_USERS_GROUP_GUID = 'daabebf8-f3b3-4f62-a23d-522eff9b7f68';

    use TLockedAccessibleCollection;
}
 