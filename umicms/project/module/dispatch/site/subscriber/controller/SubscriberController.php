<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\site\subscriber\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umicms\project\module\dispatch\model\object\BaseSubscriber;
use umicms\project\module\dispatch\model\object\Subscriber;
use umicms\project\module\dispatch\model\DispatchModule;
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
     * @var Subscriber $subscriber подписчик
     */
    protected $subscriber;

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
        $type = $this->getRouteVar('type', Subscriber::TYPE_NAME);
        $this->subscriber = $this->module->subscriber()->add($type);

        return $this->module->subscriber()->getForm(Subscriber::FORM_SUBSCRIBE_SITE, Subscriber::TYPE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        /**
         * @var IFormElement $emailInput
         */
        $emailInput = $form->get(Subscriber::FIELD_EMAIL);
        $this->module->subscribe($emailInput->getValue(), $this->subscriber);
        $this->commit();

        //$this->errors[] = $this->translate('Error subscribe');

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
            'authenticated' => $this->module->isAuthenticated()
        ];
    }

}