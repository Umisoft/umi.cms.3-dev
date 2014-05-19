<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\users\api\object\BaseUser;

/**
 * Коллекция для работы с пользователями.
 *
 * @method CmsSelector|BaseUser[] select() Возвращает селектор для выбора пользователей.
 * @method BaseUser get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает пользователя по GUID.
 * @method BaseUser getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает пользователя по id.
 * @method BaseUser add($typeName = IObjectType::BASE) Создает и возвращает пользователя.
 */
class UserCollection extends SimpleCollection
    implements IRecyclableCollection, IActiveAccessibleCollection, ILockedAccessibleCollection
{
    use TRecyclableCollection;
    use TLockedAccessibleCollection;
    use TActiveAccessibleCollection {
        TActiveAccessibleCollection::deactivate as deactivateInternal;
    }

    /**
     * Настройка для регистрации пользователей только после активации
     */
    const SETTING_REGISTRATION_WITH_ACTIVATION = 'registrationWithActivation';
    /**
     * Настройка для минимальной длины пароля
     */
    const SETTING_MIN_PASSWORD_LENGTH = 'minPasswordLength';
    /**
     * Настройка для запрещения совпадения пароля с логином
     */
    const SETTING_FORBID_PASSWORD_LOGIN_EQUALITY = 'forbidPasswordLoginEquality';
    /**
     * Настройка для групп зарегистрированных пользователей по умолчанию
     */
    const SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS = 'registeredUsersDefaultGroupGuids';

    /**
     * {@inheritdoc}
     */
    public function deactivate(IActiveAccessibleObject $object)
    {
        if ($object instanceof ILockedAccessibleObject && $object->locked) {
            throw new NotAllowedOperationException('Cannot deactivate locked user.');
        }

        return $this->deactivateInternal($object);
    }

    /**
     * Проверяет, необходимость активации при регистрации пользователя.
     * @return bool
     */
    public function getIsRegistrationWithActivation()
    {
        return (bool) $this->getSetting(self::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS);
    }

    /**
     * Возвращает минимальную длину пароля. 0, если длина неограничена.
     * @return bool
     */
    public function getMinPasswordLength()
    {
        return (int) $this->getSetting(self::SETTING_MIN_PASSWORD_LENGTH);
    }

    /**
     * Проверяет, запрещено ли совпадение пароля с логином.
     * @return bool
     */
    public function getIsPasswordAndLoginEqualityForbidden()
    {
        return (bool) $this->getSetting(self::SETTING_FORBID_PASSWORD_LOGIN_EQUALITY);
    }

    /**
     * Возвращает список GUID групп по умолчанию для зарегистрированных пользователей.
     * @return array
     */
    public function getRegisteredUsersDefaultGroupGuids()
    {
        return (array) $this->getSetting(self::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS);
    }

}