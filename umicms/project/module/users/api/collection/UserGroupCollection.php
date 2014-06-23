<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\api\collection;

use umi\i18n\ILocalesService;
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
 * @method UserGroup get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает группу пользователей по GUID.
 * @method UserGroup getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает группу пользователей по id.
 * @method UserGroup add($typeName = IObjectType::BASE) Создает и возвращает группу пользователей.
 */
class UserGroupCollection extends SimpleCollection implements ILockedAccessibleCollection
{
    use TLockedAccessibleCollection;
}
 