<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\authentication;

use umi\authentication\storage\SessionStorage;
use umi\http\IHttpAware;
use umi\http\THttpAware;
use umi\orm\exception\RuntimeException;
use umicms\project\Bootstrap;
use umicms\project\module\users\api\UsersModule;
use umicms\project\module\users\api\object\Guest;

/**
 * {@inheritdoc}
 */
class CmsAuthStorage extends SessionStorage implements IHttpAware
{
    use THttpAware;

    /**
     * @var UsersModule $api
     */
    protected $api;

    /**
     * @param UsersModule $api
     */
    public function __construct(UsersModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity()
    {
        $userId = parent::getIdentity();

        return $this->api->user()->getById($userId);
    }

    /**
     * Возвращает гостя.
     * @return Guest
     */
    public function getGuestIdentity()
    {
        return $this->api->getGuest();
    }

    /**
     * {@inheritdoc}
     */
    public function hasIdentity()
    {
        if (!$this->getHttpRequest()->cookies->has(Bootstrap::SESSION_COOKIE_NAME)) {
            return false;
        }

        if ($this->hasSessionVar(self::ATTRIBUTE_NAME)) {
            try {
                $this->getIdentity();

                return true;
            } catch (RuntimeException $e) {
                $this->clearIdentity();
            }
        }

        return false;

    }

}
 