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
        //TODO


        $this->getObjectPersister()->commit();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResponseContent()
    {
        return [
            'authenticated' => $this->api->isAuthenticated(),
            'user' => $this->user,
            'page' => $this->getCurrentPage()
        ];
    }
}
 