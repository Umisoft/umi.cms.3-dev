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

use umi\config\entity\IConfig;
use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\selector\condition\IFieldConditionGroup;
use umicms\exception\InvalidArgumentException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\NotAllowedOperationException;
use umicms\exception\UnexpectedValueException;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\CmsCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\object\BaseUser;
use umicms\Utils;

/**
 * Коллекция для работы с пользователями.
 *
 * @method CmsSelector|BaseUser[] select() Возвращает селектор для выбора пользователей.
 * @method BaseUser get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает пользователя по GUID.
 * @method BaseUser getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает пользователя по id.
 * @method BaseUser add($typeName = IObjectType::BASE) Создает и возвращает пользователя.
 */
class UserCollection extends CmsCollection implements IActiveAccessibleCollection, ILockedAccessibleCollection
{
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
     * Возвращает пользователя по коду активации.
     * @param string $activationCode
     * @param bool $active активность пользователя
     * @throws InvalidArgumentException если код активации невалидный
     * @throws NonexistentEntityException если пользователя с таким ключом активации не существует
     * @return RegisteredUser
     */
    public function getUserByActivationCode($activationCode, $active = false)
    {
        if (!Utils::checkGUIDFormat($activationCode)) {
            throw new InvalidArgumentException(
                $this->translate('Wrong activation code format.')
            );
        }

        $user = $this->getInternalSelector()
            ->where(RegisteredUser::FIELD_ACTIVATION_CODE)
                ->equals($activationCode)
            ->where(RegisteredUser::FIELD_ACTIVE)
                ->equals($active)
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$user instanceof RegisteredUser) {
            throw new NonexistentEntityException(
                $this->translate('Cannot find user by activation code.')
            );
        }

        return $user;
    }

    /**
     * Возвращает пользователя по логину или email
     * @param string $emailOrLogin логин или email
     * @throws NonexistentEntityException если не существует пользователя ни с таким логином ни email
     * @return RegisteredUser
     */
    public function getUserByLoginOrEmail($emailOrLogin)
    {
        $user = $this->getInternalSelector()
            ->begin(IFieldConditionGroup::MODE_OR)
            ->where(RegisteredUser::FIELD_LOGIN)
                ->equals($emailOrLogin)
            ->where(RegisteredUser::FIELD_EMAIL)
                ->equals($emailOrLogin)
            ->end()
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$user instanceof RegisteredUser) {
            throw new NonexistentEntityException(
                $this->translate('Cannot find user by login or email.')
            );
        }

        return $user;
    }

    /**
     * Проверяет уникальность логина пользователя.
     * @param RegisteredUser $user
     * @return bool
     */
    public function checkLoginUniqueness(RegisteredUser $user)
    {
        $users = $this->getInternalSelector()
            ->fields([RegisteredUser::FIELD_IDENTIFY])
            ->where(RegisteredUser::FIELD_LOGIN)
                ->equals($user->login)
            ->where(RegisteredUser::FIELD_IDENTIFY)
                ->notEquals($user->getId())
            ->getResult();

        return !count($users);
    }

    /**
     * Проверяет уникальность e-mail пользователя.
     * @param RegisteredUser $user
     * @return bool
     */
    public function checkEmailUniqueness(RegisteredUser $user)
    {
        $users = $this->getInternalSelector()
            ->fields([RegisteredUser::FIELD_IDENTIFY])
            ->where(RegisteredUser::FIELD_EMAIL)
                ->equals($user->email)
            ->where(RegisteredUser::FIELD_IDENTIFY)
                ->notEquals($user->getId())
            ->getResult();

        return !count($users);
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
     * @throws UnexpectedValueException если в конфигурации хранится некорректное значение
     * @return array
     */
    public function getRegisteredUsersDefaultGroupGuids()
    {
        $groupGuids = $this->getSetting(self::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS);

        if ($groupGuids instanceof IConfig) {
            $groupGuids = $groupGuids->toArray();
        }
        if (is_null($groupGuids)) {
            $groupGuids = [];
        }

        if (!is_array($groupGuids)) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Value for option "{option}" should be an array.',
                    ['option' => self::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS]
                )
            );
        }

        return  $groupGuids;
    }

}