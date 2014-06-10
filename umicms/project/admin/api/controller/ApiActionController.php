<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
use umi\i18n\TLocalesAware;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\controller\BaseCmsController;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\i18n\CmsLocalesService;
use umicms\project\admin\AdminApplication;
use umicms\project\admin\api\ApiApplication;
use umicms\project\module\users\api\UsersModule;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\Utils;

/**
 * Контроллер действий авторизации пользователя в административной панели.
 */
class ApiActionController extends BaseCmsController implements ILocalesAware, ISessionAware
{
    use TSessionAware;
    use TLocalesAware;

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

        if ($requestMethod == 'GET') {
            if ($action == 'form') {
                return $this->actionForm();
            }
            if ($action =='auth') {
                return $this->actionAuthInfo();
            }
        }

        if ($requestMethod == 'PUT' || $requestMethod == 'POST') {
            if ($action == 'login') {
                return $this->actionAuth();
            }
            if ($action == 'logout') {
                return $this->actionLogout();
            }
        }



        throw new HttpNotFound('Action not found.');
    }

    /**
     * Возвращает информацию об авторизованном пользователе и его правах.
     * @throws HttpForbidden
     * @throws HttpUnauthorized
     * @return Response
     */
    protected function actionAuthInfo()
    {
        if (!$this->api->isAuthenticated()) {
            throw new HttpUnauthorized(
                $this->translate('Authentication required.')
            );
        }

        return $this->createViewResponse(
            'auth', $this->getAuthUserInfo()
        );
    }

    /**
     * Выполняет авторизацию пользователя.
     * @throws HttpForbidden если у пользователя нет прав на административную панель
     * @throws HttpUnauthorized если логин или пароль неверны
     * @return Response
     */
    protected function actionAuth()
    {
        if ($this->api->isAuthenticated()) {
            $this->api->logout();
        }

        if (!$this->api->login($this->getPostVar('login'), $this->getPostVar('password'))) {
            throw new HttpUnauthorized(
                $this->translate('Incorrect login or password.')
            );
        }


        $response = $this->createViewResponse('auth', $this->getAuthUserInfo());

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
     * Возвращает информацию об авторизованном пользователе и его правах
     * @throws HttpForbidden если пользователю запрещен вход в административную панель
     * @return array
     */
    protected function getAuthUserInfo()
    {
        $user = $this->api->getCurrentUser();

        if (!$user->isAllowed($this->getComponent(), 'controller:settings')) {
            throw new HttpForbidden(
                $this->translate('Access denied.')
            );
        }

        return [
            'user' => $user,
            'token' => $this->getCsrfToken(),
            'locale' => $this->getCurrentLocale(),
            'isSettingsAllowed' => $this->isSettingsAllowed()
        ];
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

    /**
     * Возвращает имя контейнера сессии.
     * @return string
     */
    protected function getSessionNamespacePath()
    {
        return 'umicms';
    }

    /**
     * Проверяет, имеет ли пользователь доступ к настройкам
     * @return bool
     */
    private function isSettingsAllowed()
    {
        if ($this->api->isAuthenticated()) {
            $settings = $this->getContext()->getDispatcher()->getComponentByPath(CmsDispatcher::ADMIN_SETTINGS_COMPONENT_PATH);
            if ($this->api->getCurrentUser()->isAllowed($settings, 'controller:settings')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Генерирует и возвращает csrf-токен.
     * @return string
     */
    private function getCsrfToken()
    {
        if (!$token = $this->getSessionVar('token')) {
            $token = Utils::generateGUID();
            $this->setSessionVar('token', $token);
        }

        return $token;
    }


}
 