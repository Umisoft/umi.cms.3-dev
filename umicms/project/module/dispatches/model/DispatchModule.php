<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model;

use umi\authentication\IAuthenticationAware;
use umi\authentication\TAuthenticationAware;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatches\model\collection\DispatchCollection;
use umicms\project\module\dispatches\model\collection\SubscriberCollection;
use umicms\project\module\dispatches\model\object\Dispatch;
use umicms\project\module\users\model\object\BaseUser;
use umicms\project\module\users\model\object\Guest;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Модуль "Рассылки".
 */
class DispatchModule extends BaseModule implements IAuthenticationAware
{
    use TAuthenticationAware;

    /**
     * Возвращает репозиторий для работы с подписчиками.
     * @return SubscriberCollection
     */
    public function subscriber()
    {
        return $this->getCollection('dispatchSubscriber');
    }

    /**
     * Возвращает репозиторий для работы с рассылками.
     * @return DispatchCollection
     */
    public function dispatch()
    {
        return $this->getCollection('dispatch');
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
     * Возвращает селектор для выборки рассылки.
     * @return CmsSelector|Dispatch[]
     */
    public function getDispatches()
    {
        $dispatch = $this->dispatch()
            ->select()
            ->orderBy(Dispatch::FIELD_IDENTIFY, CmsSelector::ORDER_ASC);

        return $dispatch;
    }

    /**
     * Возвращает селектор для выборки рассылки по группам пользователей.
     * @return CmsSelector|Dispatch[]
     */
    public function getDispatchFilterGroup()
    {
        /**
         * @var UsersModule $usersModule
         * @var RegisteredUser|Guest $currentUser
         * @var array $groups
         */
        $usersModule = $this->getApi(UsersModule::className());
        $currentUser = $usersModule->isAuthenticated() ? $usersModule->getCurrentUser() : $usersModule->getGuest();

        $dispatches = $this->getDispatches();

        if ($groups = $currentUser->getProperty(BaseUser::FIELD_GROUPS)
            ->getValue()
        ) {
            foreach ($groups as $group) {
                $dispatches->where(Dispatch::FIELD_GROUP_USER)
                    ->equals($group);
            };
        }

        return $dispatches;
    }
}
