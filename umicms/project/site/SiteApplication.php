<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\IHttpAware;
use umi\http\Request;
use umi\http\Response;
use umi\http\THttpAware;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\base\component\SiteComponent;
use umicms\project\config\ISiteSettingsAware;
use umicms\project\config\TSiteSettingsAware;
use umicms\project\module\structure\api\StructureApi;
use umicms\serialization\ISerializationAware;
use umicms\serialization\TSerializationAware;

/**
 * Приложение сайта.
 */
class SiteApplication extends SiteComponent implements IHttpAware, IToolkitAware, ISiteSettingsAware, ISerializationAware
{
    use TSiteSettingsAware;
    use THttpAware;
    use TToolkitAware;
    use TSerializationAware;

    /**
     * Имя настройки для задания guid главной страницы
     */
    const SETTING_DEFAULT_PAGE_GUID = 'default-page';
    /**
     * Имя настройки для задания guid главной страницы
     */
    const SETTING_DEFAULT_LAYOUT_GUID = 'default-layout';
    /**
     * Имя настройки для задания постфикса всех URL
     */
    const SETTING_URL_POSTFIX = 'url-postfix';
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
     * @param StructureApi $structureApi
     */
    public function __construct($name, $path, array $options = [], StructureApi $structureApi)
    {
        parent::__construct($name, $path, $options, $structureApi);

        $this->registerSiteSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchResponse(IDispatchContext $context, Response $response)
    {
        $request = $context->getDispatcher()->getCurrentRequest();
        $routePath = $request->getPathInfo();
        if ($suffix = $request->getRequestFormat(null)) {
            $routePath = substr($routePath, 0, -strlen($suffix) - 1);
        }

        $isRootPath = trim($routePath, '/') === trim($context->getBaseUrl(), '/');

        if (!$isRootPath && $redirectResponse = $this->processUrlPostfixRedirect($request)) {
            return $redirectResponse;
        }

        if (!$isRootPath && $redirectResponse = $this->processDefaultPageRedirect($context)) {
            return $redirectResponse;
        }

        $requestFormat = $this->getRequestFormatByPostfix($request->getRequestFormat(null));

        if ($requestFormat !== self::DEFAULT_REQUEST_FORMAT) {
            $result = [
                'result' => $response->getContent()
            ];

            $serializer = $this->getSerializer($requestFormat, $result);
            $serializer->init();
            $serializer($result);
            $response->setContent($serializer->output());
        }

        return $response;
    }

    /**
     * Производит определение формата запроса по постфиксу
     * @param string $postfix
     * @throws HttpNotFound если постфикс запроса не поддерживается приложением
     * @return string
     */
    protected function getRequestFormatByPostfix($postfix)
    {
        if (is_null($postfix) || $postfix === $this->getSiteUrlPostfix()) {
            return self::DEFAULT_REQUEST_FORMAT;
        }

        if (!in_array($postfix, $this->supportedRequestPostfixes)) {
            throw new HttpNotFound($this->translate(
                'Url postfix "{postfix}" is not supported.',
                ['postfix' => $postfix]
            ));
        }

        return $postfix;
    }

    /**
     * Производит редирект на url с постфиксом, если он задан в настройках
     * и запрос выполнен без его указания.
     * @param Request $request
     * @return Response|null
     */
    protected function processUrlPostfixRedirect(Request $request)
    {
        $postfix = $this->getSiteUrlPostfix();

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
     * @param IDispatchContext $context
     * @return Response|null
     */
    protected function processDefaultPageRedirect(IDispatchContext $context)
    {
        $currentElement = $this->structureApi->getCurrentElement();
        if ($currentElement->getGUID() === $this->getSiteDefaultPageGuid()) {

            $response = $this->createHttpResponse();
            $response->headers->set('Location', $context->getBaseUrl());
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

        $this->getToolkit()
            ->registerAwareInterface(
            'umicms\project\config\ISiteSettingsAware',
            function ($object) {
                if ($object instanceof ISiteSettingsAware) {
                    $object->setSiteSettings($this->getSettings());
                }
            }
        );
    }

}
