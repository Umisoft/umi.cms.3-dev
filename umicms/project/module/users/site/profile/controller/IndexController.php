<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\profile\controller;

use umi\form\IForm;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\module\users\model\object\AuthorizedUser;
use umicms\project\module\users\model\UsersModule;
use umicms\hmvc\controller\site\BaseSitePageController;
use umicms\hmvc\controller\site\TFormController;

/**
 * Контроллер сохранения профиля пользователя
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
        return $this->module->user()->getForm(
            AuthorizedUser::FORM_EDIT_PROFILE,
            AuthorizedUser::TYPE_NAME,
            $this->module->getCurrentUser()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->getObjectPersister()->commit();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResponseContent()
    {
        return [
            'user' => $this->module->getCurrentUser(),
            'page' => $this->getCurrentPage()
        ];
    }
}
 