<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\rest\controller;

use Symfony\Component\HttpFoundation\Cookie;
use umi\filter\IFilterFactory;
use umi\form\element\Password;
use umi\form\element\Select;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\form\IForm;
use umi\form\IFormAware;
use umi\form\TFormAware;
use umi\hmvc\exception\http\HttpForbidden;
use umi\hmvc\exception\http\HttpUnauthorized;
use umi\http\Response;
use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umi\validation\IValidatorFactory;
use umicms\exception\OutOfBoundsException;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\component\admin\BaseController;
use umicms\hmvc\component\admin\TActionController;
use umicms\i18n\CmsLocalesService;
use umicms\project\admin\AdminApplication;
use umicms\project\module\users\model\UsersModule;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\Utils;

/**
 * Контроллер действий авторизации пользователя в административной панели.
 */
class ActionController extends BaseController implements ILocalesAware, ISessionAware, IFormAware
{
    use TSessionAware;
    use TActionController;
    use TFormAware;

    /**
     * @var UsersModule $module
     */
    protected $module;
    /**
     * @var CmsLocalesService $traitLocalesService сервис для работы с локалями
     */
    private $localesService;

    /**
     * Конструктор.
     * @param UsersModule $module
     */
    public function __construct(UsersModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocalesService(ILocalesService $localesService)
    {
        $this->localesService = $localesService;
    }

    /**
     * Возвращает информацию об авторизованном пользователе и его правах.
     * @throws HttpForbidden
     * @throws HttpUnauthorized
     * @return array
     */
    protected function actionAuth()
    {
        if (!$this->module->isAuthenticated()) {
            throw new HttpUnauthorized(
                $this->translate('Authentication required.')
            );
        }

        return $this->getAuthUserInfo();
    }

    /**
     * Выполняет авторизацию пользователя.
     * @throws HttpForbidden если у пользователя нет прав на административную панель
     * @throws HttpUnauthorized если логин или пароль неверны
     * @return Response
     */
    protected function actionLogin()
    {
        if ($this->module->isAuthenticated()) {
            $this->module->logout();
        }

        if (!$this->module->login($this->getPostVar('login'), $this->getPostVar('password'))) {
            throw new HttpUnauthorized(
                $this->translate('Incorrect login or password.')
            );
        }

        $locale = $this->getPostVar('locale');
        if ($locale) {
            $this->getLocalesService()->setCurrentLocale($locale);
        }

        $response = $this->createViewResponse('auth', $this->getAuthUserInfo());

        if ($locale) {
            $this->getLocalesService()->setCurrentLocale($locale);
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
        $user = $this->module->getAuthenticatedUser();

        if (!$user->isComponentResourceAllowed($this->getComponent(), 'controller:settings')) {
            throw new HttpForbidden(
                $this->translate('Access denied.')
            );
        }

        return [
            'user' => $user,
            'token' => $this->getCsrfToken(),
            'locale' => $this->getLocalesService()->getCurrentLocale()
        ];
    }


    /**
     * Выполняет разавторизацию пользователя.
     * @return string
     */
    protected function actionLogout()
    {
        $this->module->logout();

        return '';
    }

    /**
     * Возвращает форму авторизации пользователя.
     * @return IForm
     */
    protected function actionForm()
    {
        $localeService = $this->getLocalesService();
        $adminLocales = $localeService->getAdminLocales();
        $locales = [];
        foreach ($adminLocales as $adminLocale) {
            $locales[$adminLocale->getId()] = $adminLocale->getId();
        }

        if ($localeId = $this->getQueryVar('locale')) {
            if (array_key_exists($localeId, $locales)) {
                $localeService->setCurrentLocale($localeId);
            } else {
                $localeService->setCurrentLocale($localeService->getDefaultAdminLocaleId());
            }
        }

        $form = $this->createForm([
            'options' => [
                'dictionaries' => [
                    'collection.user', 'collection'
                ],
            ],
            'attributes' => [
                'method' => 'post'
            ]
        ]);

        $loginInput = $this->createFormEntity(
            RegisteredUser::FIELD_LOGIN,
            [
                'type' => Text::TYPE_NAME,
                'label' => RegisteredUser::FIELD_LOGIN,
                'options' => [
                    'filters' => [
                        IFilterFactory::TYPE_STRING_TRIM => []
                    ],
                    'validators' => [
                        IValidatorFactory::TYPE_REQUIRED => []
                    ]
                ]
            ]
        );

        $form->add($loginInput);

        $passwordInput = $this->createFormEntity(
            RegisteredUser::FIELD_PASSWORD,
            [
                'type' => Password::TYPE_NAME,
                'label' => RegisteredUser::FIELD_PASSWORD,
                'options' => [
                    'filters' => [
                        IFilterFactory::TYPE_STRING_TRIM => []
                    ],
                    'validators' => [
                        IValidatorFactory::TYPE_REQUIRED => []
                    ]
                ]
            ]
        );

        $form->add($passwordInput);

        if (count($locales) > 1) {
            $localeInput = $this->createFormEntity(
                'locale',
                [
                    'type' => Select::TYPE_NAME,
                    'label' => 'locale',
                    'options' => ['choices' => $locales]

                ]
            );

            $form->add($localeInput);
        }

        $submit = $this->createFormEntity('submit', ['type' => Submit::TYPE_NAME, 'label' => 'Log in']);
        $form->add($submit);

        $form->setAction($this->getUrlManager()->getAdminComponentActionResourceUrl($this->getComponent(), 'login'));

        return $form->getView();

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
 