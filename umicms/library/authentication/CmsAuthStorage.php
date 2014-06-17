<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\authentication;

use umi\authentication\storage\SessionStorage;
use umi\http\IHttpAware;
use umi\http\THttpAware;
use umi\orm\exception\RuntimeException;
use umicms\project\Bootstrap;
use umicms\project\module\users\model\UsersModule;
use umicms\project\module\users\model\object\Guest;

/**
 * {@inheritdoc}
 */
class CmsAuthStorage extends SessionStorage implements IHttpAware
{
    use THttpAware;

    /**
     * @var UsersModule $module
     */
    protected $module;

    /**
     * @param UsersModule $module
     */
    public function __construct(UsersModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity()
    {
        $userId = parent::getIdentity();

        return $this->module->user()->getById($userId);
    }

    /**
     * Возвращает гостя.
     * @return Guest
     */
    public function getGuestIdentity()
    {
        return $this->module->getGuest();
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
 