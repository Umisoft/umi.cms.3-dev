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

use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseLinkWidget;
use umicms\exception\InvalidArgumentException;
use umicms\project\module\dispatches\model\DispatchModule;
use umicms\project\module\dispatches\model\object\Dispatch;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\Subscription;
use umicms\project\module\dispatches\model\object\Unsubscription;

/**
 * Виджет для вывода списка рассылок
 */
class ManageSubscriptionLink extends BaseLinkWidget
{
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
     * @var string $isContains проверка существования рассылки у данного подписчика
     */
    public $isContains;

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
        $this->isContains = $this->subscriber->dispatches->contains($this->dispatch);
        $type = $this->isContains ? Unsubscription::TYPE_NAME : Subscription::TYPE_NAME;

        return $this->getUrl('index', ['type'=> $type, 'id'=> $this->dispatch->getId()]);
    }

    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam string $url URL ссылки
     * @templateParam string $viewName имя для отображения
     *
     * @return CmsView
     */
    public function __invoke()
    {
        return $this->createResult(
            $this->template,
            [
                'url' => $this->getLinkUrl(),
                'viewName' => $this->isContains ? 'unsubcribe' : 'subscribe'
            ]
        );
    }

}
 