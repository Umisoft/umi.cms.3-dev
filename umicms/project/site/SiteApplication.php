<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site;

use umi\config\entity\IConfig;
use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\exception\http\HttpException;
use umi\http\IHttpAware;
use umi\http\Request;
use umi\http\Response;
use umi\http\THttpAware;
use umi\orm\collection\BaseCollection;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\exception\InvalidLicenseException;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\TCmsCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\selector\CmsSelector;
use umicms\project\Bootstrap;
use umicms\hmvc\component\site\SiteComponent;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;

/**
 * Приложение сайта.
 */
class SiteApplication extends SiteComponent
    implements IHttpAware, IToolkitAware, ISerializationAware, IUrlManagerAware, ISessionAware
{
    use THttpAware;
    use TToolkitAware;
    use TSerializationAware;
    use TUrlManagerAware;
    use TSiteSettingsAware;
    use TSessionAware;

    /**
     * Имя настройки для задания guid главной страницы
     */
    const SETTING_DEFAULT_PAGE_GUID = 'defaultPage';
    /**
     * Имя настройки для задания guid шаблона по умолчанию
     */
    const SETTING_DEFAULT_LAYOUT_GUID = 'defaultLayout';
    /**
     * Имя настройки для задания title страниц по умолчанию
     */
    const SETTING_DEFAULT_TITLE = 'defaultMetaTitle';
    /**
     * Имя настройки для задания префикса title страниц
     */
    const SETTING_TITLE_PREFIX = 'metaTitlePrefix';
    /**
     * Имя настройки для задания keywords страниц по умолчанию
     */
    const SETTING_DEFAULT_KEYWORDS = 'defaultMetaKeywords';
    /**
     * Имя настройки для задания description страниц по умолчанию
     */
    const SETTING_DEFAULT_DESCRIPTION = 'defaultMetaDescription';
    /**
     * Имя настройки для задания шаблонизатора по умолчанию
     */
    const SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE = 'defaultTemplatingEngineType';
    /**
     * Имя настройки для задания расширения файлов с шаблонами по умолчанию
     */
    const SETTING_DEFAULT_TEMPLATE_EXTENSION = 'defaultTemplateExtension';
    /**
     * Имя настройки для задания директории общих шаблонов
     */
    const SETTING_COMMON_TEMPLATE_DIRECTORY = 'commonTemplateDirectory';
    /**
     * Имя настройки для задания директории шаблонов
     */
    const SETTING_TEMPLATE_DIRECTORY = 'templateDirectory';
    /**
     * Имя настройки для включения/выключения кэширования страниц браузером
     */
    const SETTING_BROWSER_CACHE_ENABLED = 'browserCacheEnabled';
    /**
     * Опция для задания сериализаторов приложения
     */
    const OPTION_SERIALIZERS = 'serializers';
    /**
     * Формат запроса по умолчанию.
     */
    const DEFAULT_REQUEST_FORMAT = 'html';

    /**
     * @var array $supportedRequestPostfixes список поддерживаемых постфиксов запроса
     */
    protected $supportedRequestPostfixes = ['json', 'xml'];

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $path, array $options = [])
    {
        parent::__construct($name, $path, $options);

        $this->registerSiteSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        $this->checkLicense();

        /*if ($response = $this->postRedirectGet($request)) {
            return $response; //TODO разобраться, почему проблема в xslt
        }*/

        $this->registerSelectorInitializer();
        $this->registerSerializers();

        while (!$this->getPageCallStack()->isEmpty()) {
            $this->getPageCallStack()->pop();
        }

        $element = null;
        if (isset($context->getRouteParams()[self::MATCH_STRUCTURE_ELEMENT])) {
            $element = $context->getRouteParams()[self::MATCH_STRUCTURE_ELEMENT];

            if ($element instanceof ICmsPage) {
                if ($element instanceof CmsHierarchicObject) {
                    foreach ($element->getAncestry() as $parent) {
                        $this->pushCurrentPage($parent);
                    }
                }

                $this->pushCurrentPage($element);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchResponse(IDispatchContext $context, Response $response)
    {
        $request = $context->getDispatcher()->getCurrentRequest();

        $currentPath = $request->getPathInfo();
        $suffix = $request->getRequestFormat(null);

        if ($suffix) {
            $currentPath = substr($currentPath, 0, -strlen($suffix) - 1);
        }

        $isRootPath = $currentPath === $this->getUrlManager()->getProjectUrl();

        if (!$isRootPath && $redirectResponse = $this->processUrlPostfixRedirect($request)) {
            return $redirectResponse;
        }

        if (!$isRootPath && $redirectResponse = $this->processDefaultPageRedirect($suffix)) {
            return $redirectResponse;
        }

        $requestFormat = $request->getRequestFormat();

        if ($requestFormat !== self::DEFAULT_REQUEST_FORMAT) {

            if ($response->headers->has('content-type')) {
                throw new HttpException(Response::HTTP_NOT_FOUND, $this->translate(
                    'Cannot serialize response. Headers had been already set.'
                ));
            }

             if ($response->getIsCompleted())  {
                 $variables = ['result' => $response->getContent()];
             } else {
                 $variables = ['layout' => $response->getContent()];
             }

            $result = $this->serializeResult(
                $requestFormat,
                $variables
            );
            $response->setContent($result);

        } elseif ($this->getSiteBrowserCacheEnabled()) {
            $this->setBrowserCacheHeaders($request, $response);
        }

        return $response;
    }

    /**
     * Реализация паттерна Post/Redirect/Get - PRG.
     * @link http://en.wikipedia.org/wiki/Post/Redirect/Get
     */
    protected function postRedirectGet(Request $request)
    {
        $prgKey = 'prg_' . md5($request->getRequestUri());

        $requestFormat = $request->getRequestFormat(null);
        if ($request->getMethod() === 'POST' &&
            empty($_FILES) &&
            (is_null($requestFormat) || $requestFormat== self::DEFAULT_REQUEST_FORMAT))
        {

            $post = $request->request->all();
            $this->setSessionVar($prgKey, $post);

            $response = $this->createHttpResponse();
            $response->headers->set('Location', $request->getRequestUri());
            $response->setStatusCode(Response::HTTP_FOUND);

            return $response;

        } elseif ($request->cookies->has(Bootstrap::SESSION_COOKIE_NAME) && $this->hasSessionVar($prgKey)) {

            $request->server->set('REQUEST_METHOD', 'POST');
            $request->request->replace($this->getSessionVar($prgKey));
            $this->removeSessionVar($prgKey);

            return null;
        }
        return null;
    }

    /**
     * Возвращает настройки сайта.
     * @throws RequiredDependencyException если настройки не были установлены
     * @return IConfig
     */
    protected function getSiteSettings() {
        return $this->getSettings();
    }
    /**
     * Устанавливает ETag для браузерного кэширования страниц.
     * @param Request $request
     * @param Response $response
     */
    protected function setBrowserCacheHeaders(Request $request, Response $response) {
        $response->setETag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);
    }

    /**
     * Сериализует результат в указанный формат
     * @param string $format формат
     * @param mixed $variables список переменных
     * @return string
     */
    protected function serializeResult($format, $variables) {
        $serializer = $this->getSerializer($format, $variables);
        $serializer->init();
        $serializer($variables);

        return $serializer->output();
    }

    /**
     * Регистрирует сериализаторы, необходимые для приложения.
     */
    protected function registerSerializers()
    {
        if (isset($this->options[self::OPTION_SERIALIZERS])) {
            $serializersConfig = $this->configToArray($this->options[self::OPTION_SERIALIZERS], true);
            /**
             * @var ISerializerFactory $serializerFactory
             */
            $serializerFactory = $this->getToolkit()->getService('umicms\serialization\ISerializerFactory');

            $serializerFactory->registerSerializers($serializersConfig);
        }
    }

    /**
     * Производит редирект на url с постфиксом, если он задан в настройках
     * и запрос выполнен без его указания.
     * @param Request $request
     * @return Response|null
     */
    protected function processUrlPostfixRedirect(Request $request)
    {
        $postfix = $this->getUrlManager()->getSiteUrlPostfix();

        if ($postfix && is_null($request->getRequestFormat(null))) {
            $redirectUrl = $request->getBaseUrl() . $request->getPathInfo() . '.' . $postfix;

            if ($queryString = $request->getQueryString()) {
                $redirectUrl .= '?' . $queryString;
            }

            $response = $this->createHttpResponse();
            $response->headers->set('Location', $redirectUrl);
            $response->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);
            $response->setIsCompleted();

            return $response;
        }

        return null;
    }

    /**
     * Выполняет редирект на базовый url, если пользователь запрашивает станицу по умолчанию
     * по ее прямому url.
     * @param string|null $suffix
     * @return Response|null
     */
    protected function processDefaultPageRedirect($suffix = null)
    {
        if ($this->hasCurrentPage() && $this->getCurrentPage()->getGUID() === $this->getSiteDefaultPageGuid()) {
            $response = $this->createHttpResponse();
            $location = $this->getUrlManager()->getProjectUrl();
            if ($suffix && $suffix != $this->getUrlManager()->getSiteUrlPostfix()) {
                $location .= '.' . $suffix;
            }
            $response->headers->set('Location', $location);
            $response->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);
            $response->setIsCompleted();

            return $response;
        }

        return null;
    }

    /**
     * Регистрирует сервисы для работы сайта.
     */
    protected function registerSiteSettings()
    {
        $this->setSiteSettings($this->getSettings());

        $this->getToolkit()->registerAwareInterface(
            'umicms\project\site\config\ISiteSettingsAware',
            function ($object) {
                if ($object instanceof ISiteSettingsAware) {
                    $object->setSiteSettings($this->getSettings());
                }
            }
        );
    }

    /**
     * Регистрирует иницициализотор для всех селекторов.
     */
    protected function registerSelectorInitializer()
    {
        BaseCollection::setSelectorInitializer(
            function(CmsSelector $selector) {
                $collection = $selector->getCollection();

                if ($collection instanceof IRecyclableCollection) {
                    $selector->where(IRecyclableObject::FIELD_TRASHED)->notEquals(true);
                }

                if ($collection instanceof IActiveAccessibleCollection) {
                    $selector->where(IActiveAccessibleObject::FIELD_ACTIVE)->equals(true);
                }
            }
        );
    }

    /**
     * Проверяет лицензию.
     * @throws InvalidLicenseException в случае если произошла ошибка связанная с лицензией
     */
    private function checkLicense()
    {
        $domainKey = $this->getSiteSettings()->get('domainKey');
        $defaultDomain = $this->getDefaultDomain();

        if (empty($domainKey)) {
            throw new InvalidLicenseException($this->translate(
                'Invalid domain key.'
            ));
        }
        if (empty($defaultDomain)) {
            throw new InvalidLicenseException($this->translate(
                'Do not set the default domain.'
            ));
        }
        $licenseType = $this->getSiteSettings()->get('licenseType');
        if (empty($licenseType)) {
            throw new InvalidLicenseException($this->translate(
                'Do not set the default domain.'
            ));
        }
        if (strstr($licenseType, base64_decode('dHJpYWw='))) {
            $deactivation = $this->getSiteSettings()->get('deactivation');
            if (empty($deactivation) || base64_decode($deactivation) < time()) {
                throw new InvalidLicenseException($this->translate(
                    'License has expired.'
                ));
            }
        }
        if (!$this->checkDomainKey()) {
            throw new InvalidLicenseException($this->translate(
                'Invalid domain key.'
            ));
        }
    }

    /**
     * Формирует эталонный доменный ключ.
     * @param string $license тип лицензии, для которой генерировать доменный ключ
     * @return string
     */
    private function getSourceDomainKey($license) {
        $serverAddress = $_SERVER['SERVER_ADDR'];
        $defaultDomain = $this->getDefaultDomain();

        $licenseKeyCode = strtoupper(substr(md5($serverAddress), 0, 11) . "-" . substr(md5($defaultDomain . $license), 0, 11));

        return $licenseKeyCode;
    }

    /**
     * Возвращает домен по умолчанию.
     * @return string
     */
    private function getDefaultDomain()
    {
        $defaultDomain = $this->getSiteSettings()->get('defaultDomain');
        if (mb_strrpos($defaultDomain, 'www.') === 0) {
            $defaultDomain = mb_substr($defaultDomain, 4);
        }
        return $defaultDomain;
    }

    /**
     * Проверяет соответствие домеенного ключа полученному.
     * @return bool
     */
    private function checkDomainKey()
    {
        $domainKey = $this->getSiteSettings()->get('domainKey');
        $licenseType = $this->getSiteSettings()->get('licenseType');
        $domainKeySource = $this->getSourceDomainKey($licenseType);
        return (substr($domainKey, 12, strlen($domainKey) - 12) == $domainKeySource);
    }

}
