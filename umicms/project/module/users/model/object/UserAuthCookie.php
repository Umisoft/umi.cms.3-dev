<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model\object;

use umicms\orm\object\CmsObject;

/**
 * Кука аутентификации
 */
class UserAuthCookie extends CmsObject
{
    /** Имя поля для хранения пользователя */
    const FIELD_USER = 'user';
    /** Имя поля для хранения токена */
    const FIELD_TOKEN = 'token';
    /** Сивмол разделитель, используемый в куке */
    const DELIMITER_CHAR = ':';

    /**
     * @return RegisteredUser
     */
    public function getUser()
    {
        return $this->getProperty(self::FIELD_USER)->getValue();
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->getProperty(self::FIELD_TOKEN)->getValue();
    }

    /**
     * @param  string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->getProperty(self::FIELD_TOKEN)->setValue($token);
        return $this;
    }

    /**
     * @param  RegisteredUser $user
     * @return $this
     */
    public function setUser(RegisteredUser $user)
    {
        $this->getProperty(self::FIELD_USER)->setValue($user);
        return $this;
    }

    /**
     * Возвращает значение куки в формате {userId}DELIMITER_CHAR{guid}DELIMITER_CHAR{token}
     * @return string
     */
    public function getCookieValue()
    {
        return implode(self::DELIMITER_CHAR, [$this->getUser()->getId(), $this->getGUID(), $this->getToken()]);
    }

    /**
     * @return \DateTime
     */
    public function getCookieTTL()
    {
        return $this->updated ? : $this->created;
    }
}