<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\form\IForm;
use umi\hmvc\controller\BaseController;
use umi\hmvc\exception\http\HttpForbidden;
use umi\hmvc\exception\http\HttpNotFound;
use umi\hmvc\exception\http\HttpUnauthorized;
use umi\http\Response;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\collection\ICmsCollection;
use umicms\project\admin\api\ApiApplication;
use umicms\project\module\users\api\UsersModule;
use umicms\project\module\users\api\object\AuthorizedUser;

/**
 * Контроллер действий авторизации пользователя в административной панели.
 */
class ActionController extends BaseController implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * @var UsersModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param UsersModule $api
     */
    public function __construct(UsersModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $action = $this->getRouteVar('action');
        $requestMethod = $this->getRequest()->getMethod();

        if ($requestMethod == 'PUT' || $requestMethod == 'POST') {
            if ($action == 'login') {
                return $this->createViewResponse(
                    $action,
                    [$action => $this->actionLogin()]
                );
            }
            if ($action == 'logout') {
                $this->actionLogout();
                return $this->createResponse('', Response::HTTP_NO_CONTENT);
            }
        }

        if ($requestMethod == 'GET' && $action == 'form') {

            return $this->createViewResponse(
                $action,
                [$action => $this->actionForm()]
            );
        }

        throw new HttpNotFound('Action not found.');
    }

    /**
     * Выполняет авторизацию пользователя.
     * @throws HttpForbidden если у пользователя нет прав на административную панель
     * @throws HttpUnauthorized если логин или пароль неверны
     * @return AuthorizedUser
     */
    protected function actionLogin()
    {
        if ($this->api->isAuthenticated()) {
            $this->api->logout();
        }

        if (!$this->api->login($this->getPostVar('login'), $this->getPostVar('password'))) {
            throw new HttpUnauthorized(
                $this->translate('Incorrect login or password.')
            );
        }

        $user = $this->api->getCurrentUser();

        if (!$user->isAllowed($this->getComponent(), 'controller:settings')) {
            throw new HttpForbidden(
                $this->translate('Access denied.')
            );
        }

        return $user;
    }

    /**
     * Выполняет разавторизацию пользователя.
     * @return string
     */
    protected function actionLogout()
    {
        $this->api->logout();

        return '';
    }

    /**
     * Возвращает форму авторизации пользователя.
     * @return IForm
     */
    protected function actionForm()
    {
        /**
         * @var ICmsCollection $collection
         */
        $collection = $this->api->user()->getCollection();
        $form = $collection->getForm('authorized', 'login');
        /**
         * @var ApiApplication $component
         */
        $component = $this->getComponent();

        $form->setAction($this->getUrlManager()->getAdminComponentActionResourceUrl($component, 'login'));

        return $form;
    }

}
 