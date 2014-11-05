<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\unsubscription\controller;

use umi\form\IForm;
use umicms\exception\InvalidArgumentException;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\Dispatch;
use umicms\project\module\dispatches\model\object\Subscription;
use umicms\project\module\dispatches\model\DispatchModule;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;
use umi\orm\metadata\IObjectType;

/**
 * Контроллер отписки пользователя
 */
class IndexController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var DispatchModule $module модуль "Рассылки"
     */
    protected $module;

    /**
     * @var Subscriber $subscriber подписчик
     */
    private $subscriber;

    /**
     * @var Dispatch $dispatch рассылка
     */
    private $dispatch;

    /**
     * @var string $type тип подписчика
     */
    public $type = IObjectType::BASE;

    /**
     * Конструктор.
     * @param DispatchModule $module модуль "Рассылки"
     */
    public function __construct(DispatchModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTemplateName()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $this->subscriber = $this->module->getCurrentSubscriber();
        $token = $this->getRouteVar('token');

        if (!is_null($token)) {
        $subscription = $this->module->subscription()->getSubscriptionByToken($token);
        $this->dispatch = $subscription->getProperty(Subscription::FIELD_DISPATCH)->getValue();
    }

        if ($this->dispatch && !$this->dispatch instanceof Dispatch) {
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
        $unsubscription = $this->module->addUnsubscription($this->type, $this->dispatch);

        return $this->module->subscriber()->getForm(Subscriber::FORM_UNSUBSCRIBE_SITE,
            $unsubscription->getTypeName(),
            $unsubscription
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        if(!$this->subscriber->dispatches->contains($this->dispatch)){
            throw new InvalidArgumentException(
                $this->translate(
                    'You have unsubscribed from newsletter "{nameDispatch}".',
                    [
                        'nameDispatch' => $this->dispatch->displayName
                    ]
                )
            );
        }
        $this->module->subscriber($this->subscriber);
        $this->commit();

        return $this->buildRedirectResponse();
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница подписки
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'subscriber' => $this->subscriber,
            'unsubscribe' => !$this->subscriber->dispatches->contains($this->dispatch),
            'dispatch' => $this->dispatch
        ];
    }

}