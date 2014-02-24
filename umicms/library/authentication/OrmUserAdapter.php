<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\authentication;

use umi\authentication\adapter\ORMAdapter;
use umicms\project\module\users\object\User;

/**
 * {@inheritdoc}
 */
class OrmUserAdapter extends ORMAdapter
{
    /**
     * Проверяет правильность пароля для указанного пользователя
     * @param User $user пользователь
     * @param string $password пароль
     * @return bool
     */
    public function checkPassword(User $user, $password) {
        $passwordHash = crypt($password, $user->getProperty(User::FIELD_PASSWORD_SALT)->getValue());

        return $user->getProperty($this->passwordField)->getValue() === $passwordHash;
    }
}
 