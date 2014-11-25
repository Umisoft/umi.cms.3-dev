<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\registration\controller;

use umi\form\IForm;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер регистрации пользователя
 */
class IndexController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var UsersModule $module модуль "Пользователи"
     */
    protected $module;
    /**
     * @var RegisteredUser $user регистрируемый пользователь
     */
    private $user;

    /**
     * Конструктор.
     * @param UsersModule $module модуль "Пользователи"
     */
    public function __construct(UsersModule $module)
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
        $type = $this->getRouteVar('type', RegisteredUser::TYPE_NAME);
        $this->user = $this->module->getUserForRegistration($type);

        $form = $this->module->user()->getForm(RegisteredUser::FORM_REGISTRATION, $type, $this->user);

        $form->setAction($this->getUrl('index', ['type' => $type]));

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->module->register($this->user);
        $this->commit();

        if ($this->user->active) {
            $this->module->setAuthenticatedUser($this->user);
        }

        $this->sendNotifications();

        return $this->buildRedirectResponse();
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $success флаг, указывающий на успешное сохранение изменений
     * @templateParam bool $authenticated флаг, указывающий на то, авторизован пользователь или нет
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница регистрации пользователя
     * @templateParam umicms\project\module\users\model\object\RegisteredUser $user новый зарегистрированный пользователь текущая страница регистрации пользователя
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'user' => $this->user,
            'authenticated' => $this->module->isAuthenticated(),
            'success' => (bool) $this->user->getProperty(RegisteredUser::FIELD_ACTIVATION_CODE)->getValue(),
        ];
    }

    /**
     * Отправляет уведомления о регистрации
     */
    protected function sendNotifications()
    {
        $this->sendUserNotification();
        $this->sendAdminNotification();
    }

    /**
     * Отпраляет уведомление о регистрации пользователю
     */
    protected function sendUserNotification()
    {
        if (!$this->user->active) {
            $this->sendActivationNotification();
        } else {
            $this->sendSuccessfulRegistrationNotification();
        }
    }

    /**
     * Отпраляет пользователю письмо с кодом активации аккаунта
     */
    protected function sendActivationNotification()
    {
        $this->mail(
            [$this->user->email => $this->user->displayName],
            $this->module->getMailSender(),
            'mail/activationMailSubject',
            'mail/activationMailBody',
            [
                'activationCode' => $this->user->getProperty(RegisteredUser::FIELD_ACTIVATION_CODE)->getValue(),
                'user' => $this->user
            ]
        );
    }

    /**
     * Отпраляет письмо пользователю об успешной регистрации без активации
     */
    protected function sendSuccessfulRegistrationNotification()
    {
        $this->mail(
            [$this->user->email => $this->user->displayName],
            $this->module->getMailSender(),
            'mail/successfulRegistrationMailSubject',
            'mail/successfulRegistrationMailBody',
            [
                'user' => $this->user
            ]
        );
    }

    /**
     * Отправляет уведомления администраторам о регистрации нового пользователя
     */
    protected function sendAdminNotification()
    {
        $admins = $this->module->getNotificationRecipients();

        if ($admins) {
            $this->mail(
                $admins,
                $this->module->getMailSender(),
                'mail/adminNotificationMailSubject',
                'mail/adminNotificationMailBody',
                [
                    'user' => $this->user
                ]
            );
        }
    }
}
 