<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model;

use umi\authentication\exception\RuntimeException;
use umi\authentication\IAuthenticationAware;
use umi\authentication\IAuthenticationFactory;
use umi\authentication\TAuthenticationAware;
use umicms\module\BaseModule;
use umicms\project\module\users\model\collection\UserCollection;
use umicms\project\module\users\model\collection\UserGroupCollection;
use umicms\project\module\users\model\object\AuthorizedUser;
use umicms\project\module\users\model\object\Guest;
use umicms\project\module\users\model\object\Supervisor;
use umicms\project\module\users\model\object\UserGroup;
use umicms\Utils;

/**
 * Модуль для работы с пользователями.
 */
class UsersModule extends BaseModule implements IAuthenticationAware
{
    use TAuthenticationAware;

    /**
     * Настройка отправителя писем
     */
    const SETTING_MAIL_SENDER = 'mailFromEmail';
    /**
     * Настройка получателей уведомлений
     */
    const SETTING_MAIL_NOTIFICATION_RECIPIENTS = 'registeredUserNotificationEmails';

    /**
     * @var string $guestGuid GUID гостя
     */
    public $guestGuid = '552802d2-278c-46c2-9525-cd464bbed63e';
    /**
     * @var string $supervisorGuid GUID супервайзера
     */
    public $supervisorGuid = '68347a1d-c6ea-49c0-9ec3-b7406e42b01e';

    /**
     * Возвращает репозиторий для работы с пользователями.
     * @return UserCollection
     */
    public function user()
    {
        return $this->getCollection('user');
    }

    /**
     * Возвращает репозиторий для работы с группами пользователей.
     * @return UserGroupCollection
     */
    public function userGroup()
    {
        return $this->getCollection('userGroup');
    }

    /**
     * Производит попытку авторизации в системе.
     * @param string $login логин пользователя
     * @param string $password пароль
     * @return bool результат авторизации
     */
    public function login($login, $password)
    {
        if ($this->isAuthenticated()) {
            return false;
        }

        $provider = $this->createAuthProvider(
            IAuthenticationFactory::PROVIDER_SIMPLE,
            [$login, $password]
        );

        return $this->getDefaultAuthManager()
            ->authenticate($provider)
            ->isSuccessful();
    }

    /**
     * Регистрирует пользователя в системе.
     * @param AuthorizedUser $user
     * @return AuthorizedUser
     */
    public function register(AuthorizedUser $user)
    {
        if ($this->user()->getIsRegistrationWithActivation()) {
            $this->user()->deactivate($user);
        } else {
            $this->user()->activate($user);
        }

        $user->updateActivationCode();

        $userGroups = $user->groups;

        $defaultGroups = $this->userGroup()
            ->select()
            ->fields([UserGroup::FIELD_GUID])
            ->where(UserGroup::FIELD_GUID)
                ->in($this->user()->getRegisteredUsersDefaultGroupGuids());

        foreach ($defaultGroups as $group)
        {
            $userGroups->link($group);
        }

        $user->registrationDate = new \DateTime();

        return $user;
    }

    /**
     * Активирует неактивированного пользователя по ключу авторизации.
     * @param string $activationCode
     * @return AuthorizedUser
     */
    public function activate($activationCode)
    {
        $user = $this->user()->getUserByActivationCode($activationCode);
        $user->updateActivationCode();
        $this->user()->activate($user);

        return $user;
    }

    /**
     * Выставляет пользователю новый пароль по ключу активации.
     * @param string $activationCode
     * @return AuthorizedUser
     */
    public function changePassword($activationCode)
    {
        return
            $this->user()->getUserByActivationCode($activationCode, true)
                ->setPassword($this->getRandomPassword())
                ->updateActivationCode();
    }

    /**
     * Генерирует псевдо случайный пароль.
     * @param int $length длина
     * @return string
     */
    public function getRandomPassword($length = 12)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $password = base64_encode(openssl_random_pseudo_bytes($length, $strong));
            if ($strong) {

                return substr($password, 0, $length);
            }
        }

        $letters = "$#@^&!1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $size = strlen($letters);

        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $c = rand(0, $size - 1);
            $password .= $letters[$c];
        }

        return $password;
    }

    /**
     * Возвращает авторизованного пользователя.
     * @throws RuntimeException если пользователь не был авторизован
     * @return AuthorizedUser авторизованный пользователь.
     */
    public function getCurrentUser()
    {
        return $this->getDefaultAuthManager()
            ->getStorage()
            ->getIdentity();
    }

    /**
     * Устанавливает авторизованного пользователя.
     * @param AuthorizedUser $user
     * @return $this
     */
    public function setCurrentUser(AuthorizedUser $user)
    {
        $this->getDefaultAuthManager()
            ->getStorage()
            ->setIdentity($user->getId());

        return $this;
    }

    /**
     * Проверяет, авторизован ли пользователь в системе.
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->getDefaultAuthManager()
            ->isAuthenticated();
    }

    /**
     * Уничтожает данные текущей авторизации.
     */
    public function logout()
    {
        $this->getDefaultAuthManager()
            ->forget();
    }

    /**
     * Возвращает гостя.
     * @return Guest
     */
    public function getGuest()
    {
        return $this->user()->get($this->guestGuid);
    }

    /**
     * Возвращает супервайзера.
     * @return Supervisor
     */
    public function getSupervisor()
    {
        return $this->user()->get($this->supervisorGuid);
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
    public function getNotificationRecipients()
    {
        return Utils::parseEmailList($this->getSetting(self::SETTING_MAIL_NOTIFICATION_RECIPIENTS));
    }

}
