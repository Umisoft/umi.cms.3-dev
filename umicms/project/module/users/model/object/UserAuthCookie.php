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

class UserAuthCookie extends CmsObject
{
    /** Имя поля для хранения пользователя */
    const FIELD_USER = 'user';
    /** Имя поля для хранения токена */
    const FIELD_TOKEN = 'token';

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
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->getProperty(self::FIELD_TOKEN)->setValue($token);
        return $this;
    }
}