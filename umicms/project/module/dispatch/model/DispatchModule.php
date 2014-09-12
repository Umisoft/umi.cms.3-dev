<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\model;

use umi\orm\selector\condition\IFieldConditionGroup;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatch\model\collection\DispatchCollection;
use umicms\project\module\dispatch\model\collection\ReasonCollection;
use umicms\project\module\dispatch\model\collection\ReleaseCollection;
use umicms\project\module\dispatch\model\collection\SubscriberCollection;
use umicms\project\module\dispatch\model\collection\TemplateMailCollection;
use umicms\project\module\dispatch\model\object\Dispatch;
use umicms\project\module\dispatch\model\object\Reason;
use umicms\project\module\dispatch\model\object\Release;
use umicms\project\module\dispatch\model\object\BaseSubscriber;
use umicms\project\module\dispatch\model\object\Subscriber;
use umicms\project\module\dispatch\model\object\TemplateMail;
use umi\authentication\IAuthenticationAware;
use umi\authentication\TAuthenticationAware;

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
        return $this->getCollection('subscriber');
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
     * Производит попытку подписания пользователя на рассылку.
     * @param Subscriber $subscriber логин пользователя
     * @return bool результат авторизации
     */
    public function subscribe(Subscriber $subscriber)
    {

        return $subscriber;
    }

    /**
     * Возвращает селектор для выборки новостей.
     * @return CmsSelector|dispatch[]
     */
    public function getDispatch()
    {
        $dispatch = $this->dispatch()->select()
            ->orderBy(Dispatch::FIELD_IDENTIFY, CmsSelector::ORDER_DESC)->end();

        return $dispatch;
    }
}
