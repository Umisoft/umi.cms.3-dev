<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\profile\password\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;
use umicms\project\module\users\site\profile\password\model\PasswordValidator;
use umicms\project\site\controller\SitePageController;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер изменения пароля пользователя
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
        $user = $this->api->getCurrentUser();
        $form = $this->api->user()->getForm(AuthorizedUser::FORM_CHANGE_PASSWORD, AuthorizedUser::TYPE_NAME, $user);

        /**
         * @var IFormElement $passwordInput
         */
        $passwordInput = $form->get('password');

        $passwordValidator = new PasswordValidator(
            'password',
            [
                'salt' => $user->getProperty(AuthorizedUser::FIELD_PASSWORD_SALT)->getValue(),
                'hash' => $user->getProperty(AuthorizedUser::FIELD_PASSWORD)->getValue(),
                'errorLabel' => 'Wrong password.',
                'message' => $this->translate('Wrong password.')
            ]
        );
        $passwordInput->getValidators()->appendValidator($passwordValidator);

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->success = true;
        $this->getObjectPersister()->commit();

        return $this->buildRedirectResponse();
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

}
 