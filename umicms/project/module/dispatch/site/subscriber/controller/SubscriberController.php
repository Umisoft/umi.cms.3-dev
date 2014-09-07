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
        return $this->module->subscriber()->getForm(Subscriber::FORM_SUBSCRIBE_SITE, Subscriber::TYPE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        if ($this->module->isAuthenticated()) {
        }

        /**
         * @var IFormElement $emailInput
         */
        $emailInput = $form->get(Subscriber::FIELD_EMAIL);

        if ($emailInput) {
            $this->errors[] = $emailInput->getValue();
            return null;

        }

        $this->errors[] = $this->translate('Invalid login or password');

        return null;
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $authenticated флаг, указывающий на то, авторизован пользователь или нет
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница авторизаци
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