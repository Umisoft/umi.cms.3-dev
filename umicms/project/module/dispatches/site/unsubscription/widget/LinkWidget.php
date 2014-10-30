<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\unsubscription\widget;

use umicms\hmvc\widget\BaseLinkWidget;
use umicms\exception\InvalidArgumentException;
use umicms\project\module\dispatches\model\DispatchModule;
use umicms\project\module\dispatches\model\object\Dispatch;
use umicms\project\module\dispatches\model\object\Subscription;

/**
 * Виджет для вывода ссылки на отписку
 */
class LinkWidget extends BaseLinkWidget
{
    /**
     * @var DispatchModule $module модуль "Рассылки"
     */
    protected $module;

    /**
     * @var string|Dispatch $dispatch рассылка или GUID рассылки
     */
    public $dispatch;

    /**
     * {@inheritdoc}
     */
    public $absolute = true;

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

        $subscriber = $this->module->getCurrentSubscriber();
        $subscription = $subscriber->dispatches->link($this->dispatch);

        return $this->getUrl('index', ['token'=>$subscription->getProperty(Subscription::FIELD_TOKEN)->getValue()], $this->absolute);
    }

}
 