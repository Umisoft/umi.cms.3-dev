<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\restoration\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umicms\exception\NonexistentEntityException;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер запроса смены пароля пользователя
 */
class IndexController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var UsersModule $module модуль "Пользователи"
     */
    protected $module;
    /**
     * @var bool $success успех операции запроса смены пароля
     */
    private $success = false;

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
        $form = $this->module->user()->getForm(RegisteredUser::FORM_RESTORE_PASSWORD, RegisteredUser::TYPE_NAME);

        $form->setAction($this->getUrl('index'));

        return $form;
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
            $user = $this->module->user()->getUserByLoginOrEmail($loginOrEmailInput->getValue());

            if (!$user->active) {
                $this->errors[] = $this->translate('User with given login or email has been block or has not activated.');
            } else {
                $user->updateActivationCode();
                $this->commit();
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
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $success флаг, указывающий на успешное сохранение изменений
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница запроса смены пароля
     *
     * @return array
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
     * @param RegisteredUser $user
     */
    protected function sendRestorePasswordConfirmation(RegisteredUser $user)
    {
        $this->mail(
            [$user->email => $user->displayName],
            $this->module->getMailSender(),
            'mail/confirmationSubject',
            'mail/confirmationBody',
            [
                'activationCode' => $user->getProperty(RegisteredUser::FIELD_ACTIVATION_CODE)->getValue(),
                'user' => $user
            ]
        );
    }

}
 