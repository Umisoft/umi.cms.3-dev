<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\registration\controller;

use umi\form\IForm;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;
use umicms\project\site\controller\SitePageController;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер сохранения профиля пользователя
 */
class IndexController extends SitePageController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

    /**
     * @var UsersModule $api API модуля "Пользователи"
     */
    protected $api;
    /**
     * @var AuthorizedUser $user регистрируемый пользователь
     */
    private $user;

    /**
     * Конструктор.
     * @param UsersModule $usersModule API модуля "Пользователи"
     */
    public function __construct(UsersModule $usersModule)
    {
        $this->api = $usersModule;
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
        $this->user = $this->api->user()->add($type);

        return $this->api->user()->getForm(AuthorizedUser::FORM_REGISTRATION, $type, $this->user);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->api->register($this->user);
        $this->getObjectPersister()->commit();

        if ($this->user->active) {
            $this->api->setCurrentUser($this->user);
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
            'authenticated' => $this->api->isAuthenticated(),
            'success' => (bool) $this->user->getProperty(AuthorizedUser::FIELD_ACTIVATION_CODE),
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
            $this->api->user()->getMailSender(),
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
            $this->api->user()->getMailSender(),
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
        $admins = $this->api->user()->getRegisteredUserNotificationRecipients();

        if (count($admins)) {

            $this->mail(
                [$this->user->email => $this->user->displayName],
                $this->api->user()->getMailSender(),
                'mail/adminNotificationMailSubject',
                'mail/adminNotificationMailBody',
                [
                    'user' => $this->user
                ]
            );
        }
    }
}
 