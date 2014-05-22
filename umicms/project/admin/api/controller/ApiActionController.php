<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use Symfony\Component\HttpFoundation\Cookie;
use umi\form\element\Select;
use umi\form\IEntityFactory;
use umi\form\IForm;
use umi\hmvc\exception\http\HttpForbidden;
use umi\hmvc\exception\http\HttpNotFound;
use umi\hmvc\exception\http\HttpUnauthorized;
use umi\http\Response;
use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\controller\BaseController;
use umicms\i18n\CmsLocalesService;
use umicms\project\admin\AdminApplication;
use umicms\project\admin\api\ApiApplication;
use umicms\project\module\users\api\UsersModule;
use umicms\project\module\users\api\object\AuthorizedUser;

/**
 * Контроллер действий авторизации пользователя в административной панели.
 */
class ApiActionController extends BaseController implements ILocalesAware
{
    /**
     * @var UsersModule $api
     */
    protected $api;
    /**
     * @var IEntityFactory $formEntityFactory фабрика сущностей формы
     */
    protected $formEntityFactory;
    /**
     * @var CmsLocalesService $traitLocalesService сервис для работы с локалями
     */
    private $localesService;

    /**
     * Конструктор.
     * @param UsersModule $api
     * @param IEntityFactory $formEntityFactory
     */
    public function __construct(UsersModule $api, IEntityFactory $formEntityFactory)
    {
        $this->api = $api;
        $this->formEntityFactory = $formEntityFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocalesService(ILocalesService $localesService)
    {
        $this->localesService = $localesService;
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
                return $this->actionLogin();
            }
            if ($action == 'logout') {
                return $this->actionLogout();
            }
        }

        if ($requestMethod == 'GET' && $action == 'form') {

            return $this->actionForm();
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

        $response = $this->createViewResponse(
            'login',
            ['login' => $user]
        );

        if ($locale = $this->getPostVar('locale')) {
            $cookie = new Cookie(
                AdminApplication::CURRENT_LOCALE_COOKIE_NAME,
                $locale,
                new \DateTime('+5 year')
            );
            $response->headers->setCookie($cookie);
        }

        return $response;

    }

    /**
     * Выполняет разавторизацию пользователя.
     * @return string
     */
    protected function actionLogout()
    {
        $this->api->logout();

        return $this->createResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Возвращает форму авторизации пользователя.
     * @return IForm
     */
    protected function actionForm()
    {
        $form = $this->api->user()->getForm(AuthorizedUser::FORM_LOGIN_ADMIN, 'authorized');

        $adminLocales = $this->getLocalesService()->getAdminLocales();
        if (count($adminLocales) > 1) {

            $locales = [];
            foreach ($adminLocales as $adminLocale) {
                $locales[$adminLocale->getId()] = $adminLocale->getId();
            }
            $localeInput = $this->formEntityFactory->createFormEntity(
                'locale',
                [
                    'type' => Select::TYPE_NAME,
                    'label' => 'locale',
                    'options' => ['choices' => $locales]

                ]
            );

            $form->add($localeInput);
        }

        /**
         * @var ApiApplication $component
         */
        $component = $this->getComponent();

        $form->setAction($this->getUrlManager()->getAdminComponentActionResourceUrl($component, 'login'));

        return $this->createViewResponse(
            'form',
            ['form' => $form->getView()]
        );

    }

    /**
     * Возвращает сервис для работы с локалями
     * @throws RequiredDependencyException если сервис не был внедрен
     * @return CmsLocalesService
     */
    protected function getLocalesService()
    {
        if (!$this->localesService) {
            throw new RequiredDependencyException(sprintf(
                'Locales service is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->localesService;
    }

}
 