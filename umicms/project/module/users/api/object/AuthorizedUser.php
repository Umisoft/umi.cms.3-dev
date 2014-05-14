<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api\object;

/**
 * Пользователь.
 *
 * @property string $login логин
 * @property string $email e-mail
 * @property string $password пароль
 */
class AuthorizedUser extends BaseUser
{
    /**
     * Имя поля для хранения логина
     */
    const FIELD_LOGIN = 'login';
    /**
     * Имя поля для хранения e-mail
     */
    const FIELD_EMAIL = 'email';
    /**
     * Поле для хранения соли для хэширования пароля.
     */
    const FIELD_PASSWORD_SALT = 'passwordSalt';
    /**
     * Имя поля для хранения пароля
     */
    const FIELD_PASSWORD = 'password';

    /**
     * Форма авторизации пользователя на сайте
     */
    const FORM_LOGIN_SITE = 'login.site';
    /**
     * Форма разавторизации пользователя на сайте
     */
    const FORM_LOGOUT_SITE = 'logout.site';
    /**
     * Форма авторизации пользователя в административной панели
     */
    const FORM_LOGIN_ADMIN = 'login.admin';
}
