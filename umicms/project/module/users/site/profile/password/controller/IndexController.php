<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\profile\password\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\module\users\model\object\AuthorizedUser;
use umicms\project\module\users\model\UsersModule;
use umicms\project\module\users\site\profile\password\model\PasswordValidator;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер изменения пароля пользователя
 */
class IndexController extends BaseSitePageController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

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
        return 'index';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $user = $this->module->getCurrentUser();
        $form = $this->module->user()->getForm(AuthorizedUser::FORM_CHANGE_PASSWORD, AuthorizedUser::TYPE_NAME, $user);

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
 