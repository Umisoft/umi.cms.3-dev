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
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\module\users\model\object\AuthorizedUser;
use umicms\project\module\users\model\UsersModule;
use umicms\project\site\controller\SitePageController;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер регистрации пользователя
 */
class IndexController extends SitePageController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

    /**
     * @var UsersModule $module модуль "Пользователи"
     */
    protected $module;
    /**
     * @var AuthorizedUser $user регистрируемый пользователь
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
        return 'index';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $type = $this->getRouteVar('type', AuthorizedUser::TYPE_NAME);
        $this->user = $this->module->user()->add($type);

        return $this->module->user()->getForm(AuthorizedUser::FORM_REGISTRATION, $type, $this->user);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->module->register($this->user);
        $this->getObjectPersister()->commit();

        if ($this->user->active) {
            $this->module->setCurrentUser($this->user);
        }

        $this->sendNotifications();

        return $this->buildRedirectResponse();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'user' => $this->user,
            'authenticated' => $this->module->isAuthenticated(),
            'success' => (bool) $this->user->getProperty(AuthorizedUser::FIELD_ACTIVATION_CODE)->getValue(),
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
                'activationCode' => $this->user->getProperty(AuthorizedUser::FIELD_ACTIVATION_CODE)->getValue(),
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

        if (count($admins)) {

            $this->mail(
                [$this->user->email => $this->user->displayName],
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
 