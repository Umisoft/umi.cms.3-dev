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
use umi\orm\metadata\IObjectType;
use umicms\exception\NonexistentEntityException;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatches\model\collection\DispatchCollection;
use umicms\project\module\dispatches\model\collection\SubscriberCollection;
use umicms\project\module\dispatches\model\collection\SubscriptionCollection;
use umicms\project\module\dispatches\model\collection\UnsubscriptionCollection;
use umicms\project\module\dispatches\model\collection\ReleaseCollection;
use umicms\project\module\dispatches\model\collection\LogdispatchCollection;
use umicms\project\module\dispatches\model\object\Dispatch;
use umicms\project\module\dispatches\model\object\Release;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\Subscription;
use umicms\project\module\dispatches\model\object\Unsubscription;
use umicms\project\module\dispatches\model\object\Logdispatch;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;
use umicms\Utils;

/**
 * Модуль "Рассылки".
 */
class DispatchModule extends BaseModule implements IAuthenticationAware
{
    use TAuthenticationAware;

    /**
     * @var UsersModule $usersModule модуль "Пользователи"
     */
    protected $usersModule;

    /**
     * @var Subscriber $currentSubscriber текущий автор блога
     */
    protected $currentSubscriber;

    /**
     * Настройка отправителя писем
     */
    const SETTING_MAIL_SENDER = 'mailFromEmail';

    /**
     * Настройка кол-ва писем в одну итерацию
     */
    const SETTING_COUNT_SEND = 'countSend';

    /**
     * Конструктор.
     * @param UsersModule $usersModule
     */
    public function __construct(UsersModule $usersModule)
    {
        $this->usersModule = $usersModule;
    }

    /**
     * Возвращает репозиторий для работы с подписчиками.
     * @return SubscriberCollection
     */
    public function subscriber()
    {
        return $this->getCollection('dispatchSubscriber');
    }

    /**
     * Возвращает репозиторий для работы с подписками.
     * @return SubscriptionCollection
     */
    public function subscription()
    {
        return $this->getCollection('dispatchSubscription');
    }

    /**
     * Возвращает репозиторий для работы с подписками.
     * @return UnsubscriptionCollection
     */
    public function unsubscription()
    {
        return $this->getCollection('dispatchUnsubscription');
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
     * Возвращает репозиторий для работы с выпусками рассылок.
     * @return ReleaseCollection
     */
    public function release()
    {
        return $this->getCollection('dispatchRelease');
    }

    /**
     * Возвращает репозиторий для работы с логами рассылок.
     * @return LogdispatchCollection
     */
    public function logdispatch()
    {
        return $this->getCollection('dispatchLogdispatch');
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
     * Выставляет статус о прочтении, если существует объект
     * @param Release $release - GUID выпуска рассылок
     * @param Subscriber $subscriber - token подписки
     * @return CmsSelector|Logdispatch[] - лог выпуска рассылки
     */
    public function setReadLogDispatch(Release $release, Subscriber $subscriber)
    {
        $logDispatch = $this->logdispatch()
            ->select()
            ->where(Logdispatch::FIELD_RELEASE)->equals($release->getId())
            ->where(Logdispatch::FIELD_SUBSCRIBERS)->equals($subscriber->getId())
            ->where(Logdispatch::FIELD_READ)->notEquals(true)
            ->limit(1)
            ->getResult()
            ->fetch();

        if($logDispatch instanceof Logdispatch){
            $logDispatch->getProperty(Logdispatch::FIELD_READ)->setValue(true);
        }

        return $logDispatch;
    }

    /**
     * Возвращает текущего подписчика.
     * Если подписчик не существует - создает нового.
     * @return Subscriber
     */
    public function getCurrentSubscriber()
    {
        if (!$this->hasCurrentSubscriber()) {
            $this->currentSubscriber = $this->subscriber()->createForUser($this->usersModule->getCurrentUser(true));
        }

        return $this->currentSubscriber;
    }

    /**
     * Проверяет, является ли текущий подписчик зарегистрированным пользователем.
     * @return bool
     */
    public function isSubscriberRegistered()
    {
        return ($this->hasCurrentSubscriber() && $this->currentSubscriber->user instanceof RegisteredUser);
    }

    /**
     * Проверяет существование текущего подписчика.
     * @return bool
     */
    public function hasCurrentSubscriber()
    {
        try {
            $this->currentSubscriber = $this->subscriber()->getByUser(
                $this->usersModule->getCurrentUser()
            );
        } catch (NonexistentEntityException $e) {}

        return $this->currentSubscriber instanceof Subscriber;
    }

    /**
     * Создает подписку от имени текущего подписчика.
     * @param string $typeName тип объекта
     * @param Dispatch $dispatch рассылка
     * @return Subscription $subscription
     */
    public function addSubscription($typeName = IObjectType::BASE, Dispatch $dispatch)
    {
        $subscription = $this->subscription()->add($typeName);
        $subscription->dispatch = $dispatch;
        $subscription->subscriber = $this->getCurrentSubscriber();

        return $subscription;
    }

    /**
     * Создает отписку от имени текущего подписчика.
     * @param string $typeName тип объекта
     * @param Dispatch $dispatch рассылка
     * @return Unsubscription
     */
    public function addUnsubscription($typeName = IObjectType::BASE, Dispatch $dispatch)
    {
        $unsubscription = $this->unsubscription()->add($typeName);
        $unsubscription->dispatch = $dispatch;
        $unsubscription->subscriber = $this->getCurrentSubscriber();
        $unsubscription->subscriber->dispatches->detach($dispatch);

        return $unsubscription;
    }

    /**
     * Создает лог рассылки
     * @param string $typeName тип объекта
     * @param string|Release $release выпуск рассылки или GUID выпуса рассылки
     * @param Subscriber $subscriber подписчик
     * @return Logdispatch
     */
    public function addLogdispatch($typeName = IObjectType::BASE, $release, Subscriber $subscriber)
    {
        $logdispatch = $this->logdispatch()->add($typeName);
        if(is_string($release)){
            $release = $this->release()->get($release);
        }
        $logdispatch->release = $release;
        $logdispatch->subscriber = $subscriber;

        return $logdispatch;
    }

    /**
     * Обновляет токен подписки
     * @param Subscription $subscription подписка
     * @return Subscription $subscription
     */
    public function updateTokenSubscription(Subscription $subscription)
    {
        $subscription->updateToken();
        return $subscription;
    }

    /**
     * Возвращает отправителя электронных писем.
     * @return array|null
     */
    public function getMailSender()
    {
        return Utils::parseEmailList($this->getSetting(self::SETTING_MAIL_SENDER));
    }

}
