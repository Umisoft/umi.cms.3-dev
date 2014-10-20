<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\subscriber\controller;

use umi\form\IForm;
use umicms\project\module\dispatches\model\object\GuestSubscriber;
use umicms\project\module\dispatches\model\DispatchModule;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер авторизации пользователя
 */
class SubscriberController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var DispatchModule $module модуль "Рассылки"
     */
    protected $module;

    /**
     * @var GuestSubscriber $subscriber подписчик
     */
    private $subscriber;

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
        $type = $this->getRouteVar('type', GuestSubscriber::TYPE_NAME);
        $this->subscriber = $this->module->subscriber()->add($type);

        return $this->module->subscriber()->getSubscribeForm(
            $this->subscriber,
            false,
            $this->module->getDispatches());
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->module->subscriber($this->subscriber);
        $this->commit();

        return $this->buildRedirectResponse();
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $authenticated флаг, указывающий на то, авторизован пользователь или нет
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница подписки
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'authenticated' => false
        ];
    }

}