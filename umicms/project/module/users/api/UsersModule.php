<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api;

use umi\authentication\IAuthenticationAware;
use umi\authentication\IAuthenticationFactory;
use umi\authentication\TAuthenticationAware;
use umicms\exception\RuntimeException;
use umicms\module\BaseModule;
use umicms\project\module\users\api\collection\UserCollection;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\object\Guest;
use umicms\project\module\users\api\object\Supervisor;

/**
 * Модуль для работы с пользователями.
 */
class UsersModule extends BaseModule implements IAuthenticationAware
{
    use TAuthenticationAware;

    /**
     * @var string $guestGuid GUID гостя
     */
    public $guestGuid = '552802d2-278c-46c2-9525-cd464bbed63e';
    /**
     * @var string $supervisorGuid GUID супервайзера
     */
    public $supervisorGuid = '68347a1d-c6ea-49c0-9ec3-b7406e42b01e';
    /**
     * @var string $passwordSalt маска соли для хэширования паролей
     */
    public $passwordSaltMask = '$2a$09${salt}$';

    /**
     * Возвращает репозиторий для работы с пользователями.
     * @return UserCollection
     */
    public function user()
    {
        return $this->getCollection('user');
    }

    /**
     * Устанавливает пользователю новый пароль.
     * @param AuthorizedUser $user авторизованный пользователь
     * @param string $password пароль
     */
    public function setUserPassword(AuthorizedUser $user, $password)
    {
        $passwordSalt = strtr($this->passwordSaltMask, [
                '{salt}' => uniqid('', true)
            ]);
        $passwordHash = crypt($password, $passwordSalt);

        $user->getProperty(AuthorizedUser::FIELD_PASSWORD_SALT)->setValue($passwordSalt);
        $user->getProperty(AuthorizedUser::FIELD_PASSWORD)->setValue($passwordHash);
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
}
