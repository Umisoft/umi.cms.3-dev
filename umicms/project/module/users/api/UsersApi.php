<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api;

use umi\authentication\exception\RuntimeException;
use umi\authentication\IAuthenticationAware;
use umi\authentication\IAuthenticationFactory;
use umi\authentication\TAuthenticationAware;
use umicms\api\BaseComplexApi;
use umicms\api\IPublicApi;
use umicms\project\module\users\object\User;

/**
 * API для работы с пользователями.
 */
class UsersApi extends BaseComplexApi implements IPublicApi, IAuthenticationAware
{

    use TAuthenticationAware;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'user';
    /**
     * @var string $passwordSalt маска соли для хэширования паролей
     */
    public $passwordSaltMask = '$2a$09${salt}$';

    /**
     * Возвращает репозиторий для работы с пользователями.
     * @return UserRepository
     */
    public function user()
    {
        return $this->getApi('umicms\project\module\users\api\UserRepository');
    }

    /**
     * Устанавливает пользователю новый пароль.
     * @param User $user авторизованный пользователь
     * @param string $password пароль
     */
    public function setUserPassword(User $user, $password)
    {
        $passwordSalt = strtr($this->passwordSaltMask, [
                '{salt}' => uniqid('', true)
            ]);
        $passwordHash = crypt($password, $passwordSalt);

        $user->getProperty(User::FIELD_PASSWORD_SALT)->setValue($passwordSalt);
        $user->getProperty(User::FIELD_PASSWORD)->setValue($passwordHash);
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
     * @return User авторизованный пользователь.
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

}
