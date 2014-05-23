<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api\collection;

use umi\config\entity\IConfig;
use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\NotAllowedOperationException;
use umicms\exception\UnexpectedValueException;
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
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\object\BaseUser;
use umicms\Utils;

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
     * Настройка отправителя писем
     */
    const SETTING_MAIL_SENDER = 'mailFromEmail';
    /**
     * Настройка получателей уведомлений
     */
    const SETTING_MAIL_NOTIFICATION_RECIPIENTS = 'registeredUserNotificationEmails';


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
     * Возвращает неактивного пользователя по коду активации.
     * @param string $activationCode
     * @throws InvalidArgumentException если код активации невалидный
     * @throws NonexistentEntityException если пользователя с таким ключом активации не существует
     * @return AuthorizedUser
     */
    public function getUserByActivationCode($activationCode)
    {
        if (!Utils::checkGUIDFormat($activationCode)) {
            throw new InvalidArgumentException(
                $this->translate('Wrong activation code format.')
            );
        }

        $user = $this->selectInactive()
            ->where(AuthorizedUser::FIELD_ACTIVATION_CODE)
                ->equals($activationCode)
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$user instanceof AuthorizedUser) {
            throw new NonexistentEntityException(
                $this->translate('Cannot find user by activation code.')
            );
        }

        return $user;
    }

    /**
     * Проверяет уникальность логина пользователя.
     * @param AuthorizedUser $user
     * @return bool
     */
    public function checkLoginUniqueness(AuthorizedUser $user)
    {
        $users = $this->selectInternal()
            ->fields([AuthorizedUser::FIELD_IDENTIFY])
            ->where(AuthorizedUser::FIELD_LOGIN)
                ->equals($user->login)
            ->where(AuthorizedUser::FIELD_IDENTIFY)
                ->notEquals($user->getId())
            ->getResult();

        return !count($users->fetchAll());
    }

    /**
     * Проверяет уникальность e-mail пользователя.
     * @param AuthorizedUser $user
     * @return bool
     */
    public function checkEmailUniqueness(AuthorizedUser $user)
    {
        $users = $this->selectInternal()
            ->fields([AuthorizedUser::FIELD_IDENTIFY])
            ->where(AuthorizedUser::FIELD_EMAIL)
                ->equals($user->email)
            ->where(AuthorizedUser::FIELD_IDENTIFY)
                ->notEquals($user->getId())
            ->getResult();

        return !count($users->fetchAll());
    }

    /**
     * Возвращает отправителя электронных писем.
     * @return array
     */
    public function getMailSender()
    {
        return Utils::parseEmailList($this->getSetting(self::SETTING_MAIL_SENDER));
    }

    /**
     * Возвращает получателей уведомлений.
     * @return array
     */
    public function getRegisteredUserNotificationRecipients()
    {
        return Utils::parseEmailList($this->getSetting(self::SETTING_MAIL_NOTIFICATION_RECIPIENTS));
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