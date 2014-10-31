<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\widget;

use umicms\hmvc\widget\BaseLinkWidget;
use umicms\exception\InvalidArgumentException;
use umicms\project\module\dispatches\model\DispatchModule;
use umicms\project\module\dispatches\model\object\Dispatch;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\Subscription;

/**
 * Виджет для вывода ссылки на подписку/отписку
 */
class ManageSubscriptionLinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    public $template = 'manageLink';

    /**
     * @var DispatchModule $module модуль "Рассылки"
     */
    protected $module;

    /**
     * @var Subscriber $subscriber подписчик
     */
    private $subscriber;

    /**
     * @var string|Dispatch $dispatch рассылка или GUID рассылки
     */
    public $dispatch;

    /**
     * Конструктор.
     * @param DispatchModule $dispatchApi модуль "Рассылки"
     */
    public function __construct(DispatchModule $dispatchApi)
    {
        $this->module = $dispatchApi;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLinkUrl()
    {
        if (is_string($this->dispatch)) {
            $this->dispatch = $this->module->dispatch()->get($this->dispatch);
        }

        if (!$this->dispatch instanceof Dispatch){
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'dispatch',
                        'class' => Dispatch::className()
                    ]
                )
            );
        }

        $this->subscriber = $this->module->getCurrentSubscriber();
        /**
         * @var string $link
        */
        $link = '';

        if ($this->subscriber->dispatches->contains($this->dispatch)) {
            /**
             * @var Subscription $subscription
             */
            $subscription = $this->subscriber->dispatches->link($this->dispatch);
            $link = $this->getUrl('unsubscription.index', ['token' => $subscription->token], $this->absolute);
        } else {
            $link = $this->getUrl('subscription.index', ['id' => $this->dispatch->getId()], $this->absolute);
        }

        return $link;
    }

}
 