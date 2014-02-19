<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\site;

use umi\config\entity\IConfig;
use umi\hmvc\component\Component;
use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\IHttpAware;
use umi\http\Request;
use umi\http\Response;
use umi\http\THttpAware;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\config\ISiteSettingsAware;
use umicms\config\TSiteSettingsAware;
use umicms\exception\UnexpectedValueException;
use umicms\module\structure\api\StructureApi;

/**
 * Приложение сайта.
 */
class SiteApplication extends Component implements IHttpAware, IToolkitAware, ISiteSettingsAware
{
    use TSiteSettingsAware;
    use THttpAware;
    use TToolkitAware;

    /**
     * Имя опции для задания настроек сайта.
     */
    const OPTION_SETTINGS = 'settings';
    /**
     * Имя настройки для задания guid главной страницы
     */
    const SETTING_DEFAULT_PAGE_GUID = 'default-page';
    /**
     * Имя настройки для задания постфикса всех URL
     */
    const SETTING_URL_POSTFIX = 'url-postfix';

    /**
     * @var StructureApi $structureApi
     */
    protected $structureApi;
    /**
     * @var string $requestFormat формат запроса к приложению
     */
    protected $currentRequestFormat = 'html';
    /**
     * @var string $defaultRequestFormat формат запроса к приложению по умолчанию
     */
    protected $defaultRequestFormat = 'html';

    /**
     * @var array $supportedRequestPostfixes список поддерживаемых постфиксов запроса
     */
    protected $supportedRequestPostfixes = [
        'json' => [],
        'xml'  => []
    ];


    /**
     * {@inheritdoc}
     * @param StructureApi $structureApi
     */
    public function __construct($name, $path, array $options = [], StructureApi $structureApi)
    {
        parent::__construct($name, $path, $options);

        $this->structureApi = $structureApi;

        $this->registerSiteSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        if (!$this->structureApi->hasCurrentElement()) {
            return null;
        }

        $routePath = $request->getPathInfo();
        if ($suffix = $request->getRequestFormat(null)) {
            $routePath = substr($routePath, 0, -strlen($suffix) - 1);
        }

        $isRootPath = trim($routePath, '/') === trim($context->getBaseUrl(), '/');
        $isMatchingComplete = $context->getRouteParams()['isMatchingComplete'];

        if (!$isRootPath && $response = $this->processUrlPostfixRedirect($request)) {
            return $response;
        }

        if ($isMatchingComplete && !$isRootPath && $response = $this->processDefaultPageRedirect($context)) {
            return $response;
        }

        $this->currentRequestFormat = $this->getRequestFormatByPostfix($request->getRequestFormat(null));

        return null;
    }

    /**
     * Производит определение формата запроса по постфиксу
     * @param string $postfix
     * @throws HttpNotFound если постфикс запроса не поддерживается приложением
     * @return string
     */
    protected function getRequestFormatByPostfix($postfix) {
        if (is_null($postfix) || $postfix === $this->getSiteUrlPostfix()) {
            return $this->defaultRequestFormat;
        }

        if (isset($this->supportedRequestPostfixes[$postfix])) {
            return $postfix;
        }

        throw new HttpNotFound($this->translate(
            'Url postfix "{postfix}" is not supported.',
            ['postfix' => $postfix]
        ));
    }

    /**
     * Производит редирект на url с постфиксом, если он задан в настройках
     * и запрос выполнен без его указания.
     * @param Request $request
     * @return Response|null
     */
    protected function processUrlPostfixRedirect(Request $request) {
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
    protected function processDefaultPageRedirect(IDispatchContext $context) {
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
    protected function registerSiteSettings() {
        $settings = isset($this->options[self::OPTION_SETTINGS]) ? $this->options[self::OPTION_SETTINGS] : null;

        if (!$settings instanceof IConfig) {
            throw new UnexpectedValueException($this->translate(
                'Site settings should be instance of IConfig.'
            ));
        }
        $this->setSiteSettings($settings);

        $this->getToolkit()->registerAwareInterface(
            'umicms\config\ISiteSettingsAware',
            function ($object) use ($settings) {
                if ($object instanceof ISiteSettingsAware) {
                    $object->setSiteSettings($settings);
                }
            }
        );
    }

}
