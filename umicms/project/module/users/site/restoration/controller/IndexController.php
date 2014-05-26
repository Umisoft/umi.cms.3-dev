<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\restoration\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umicms\exception\NonexistentEntityException;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;
use umicms\project\site\controller\SitePageController;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер запроса смены пароля пользователя
 */
class IndexController extends SitePageController
{
    use TFormController;

    /**
     * @var UsersModule $api API модуля "Пользователи"
     */
    protected $api;
    /**
     * @var bool $success успех операции запроса смены пароля
     */
    private $success = false;

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
        return $this->api->user()->getForm(AuthorizedUser::FORM_RESTORE_PASSWORD, AuthorizedUser::TYPE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        /**
         * @var IFormElement $loginOrEmailInput
         */
        $loginOrEmailInput = $form->get('loginOrEmail');

        try {
            $user = $this->api->user()->getUserByLoginOrEmail($loginOrEmailInput->getValue());

            if (!$user->active || $user->trashed) {
                $this->errors[] = $this->translate('User with given login or email has been block or has not activated.');
            } else {
                $this->success = true;
                $this->sendRestorePasswordConfirmation($user);
                return $this->buildRedirectResponse();
            }

        } catch (NonexistentEntityException $e) {
            $this->errors[] = $this->translate('User with given login or email does not exist.');
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'success' => $this->success
        ];
    }

    /**
     * Отпраляет пользователю письмо с кодом подтверждения смены пароля
     */
    protected function sendRestorePasswordConfirmation(AuthorizedUser $user)
    {
        $this->mail(
            [$user->email => $user->displayName],
            $this->api->user()->getMailSender(),
            'mail/confirmationSubject',
            'mail/confirmationBody',
            [
                'activationCode' => $user->getProperty(AuthorizedUser::FIELD_ACTIVATION_CODE)->getValue(),
                'user' => $user
            ]
        );
    }

}
 