<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project;

use umi\config\entity\IConfig;
use umi\config\io\IConfigIO;
use umi\event\IEvent;
use umi\extension\twig\TwigTemplateEngine;
use umi\hmvc\component\IComponent;
use umi\hmvc\exception\http\HttpNotFound;
use umi\hmvc\IMvcEntityFactory;
use umi\http\Request;
use umi\http\Response;
use umi\orm\manager\IObjectManager;
use umi\route\IRouteFactory;
use umi\route\IRouter;
use umi\route\result\IRouteResult;
use umi\spl\config\TConfigSupport;
use umi\templating\engine\ITemplateEngineFactory;
use umi\templating\engine\php\PhpTemplateEngine;
use umi\toolkit\IToolkit;
use umi\toolkit\Toolkit;
use umicms\exception\RuntimeException;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\hmvc\url\IUrlManager;
use umicms\i18n\AdminLocale;
use umicms\i18n\CmsLocalesService;
use umicms\i18n\SiteLocale;
use umicms\route\ProjectHostRoute;
use umicms\templating\engine\php\TemplatingPhpExtension;
use umicms\templating\engine\php\ViewPhpExtension;
use umicms\templating\engine\twig\TemplatingTwigExtension;
use umicms\templating\engine\twig\ViewTwigExtension;
use umicms\templating\engine\xslt\XsltTemplateEngine;

/**
 * Загрузчик проектов на UMI.CMS
 */
class Bootstrap
{
    use TConfigSupport;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var IComponent
     */
    private $project;

    /**
     * @var CmsDispatcher
     */
    private $dispatcher;

    /**
     * @var string
     */
    private $projectPrefix;

    /**
     * @var IRouteResult
     */
    private $route;

    /**
     * @var string
     */
    private $domainUrl;

    /**
     * @var string
     */
    private $siteUrlPostfix;

    /**
     * @var string
     */
    private $routePath;
    /**
     * Имя куки сессии.
     */
    const SESSION_COOKIE_NAME = 'UMISESSID';

    /**
     * Опция конфигурации для регистрации инструментов
     */
    const OPTION_TOOLS = 'toolkit';
    /**
     * Конфигурация иснтрументов
     */
    const OPTION_TOOLS_SETTINGS = 'settings';

    /**
     * Список разрешенных форматов запроса.
     * @var array $allowedRequestFormats
     */
    public $allowedRequestFormats = [
        'json' => 'application/json; charset=utf8',
        'xml'  => 'text/xml; charset=utf8',
    ];

    /**
     * @var Toolkit $toolkit контейнер сервисов
     */
    protected $toolkit;
    /**
     * @var IConfig $config конфигурация текущего проекта
     */
    protected $projectConfig;
    /**
     * @var string $projectDestination директория текущего проекта
     */
    protected $projectDirectory = '.';
    /**
     * @var string $projectName имя текущего проекта
     */
    protected $projectName = '.';
    /**
     * @var string $projectDumpDirectory директория дампа данных проекта
     */
    protected $projectDumpDirectory = './dump';
    /**
     * @var bool
     */
    protected $debugMode = false;

    /**
     * Конструктор.
     * @param Request|null $request запрос
     * @throws RuntimeException если не удалось сконфигурировать сервисы
     */
    public function __construct(Request $request = null)
    {
        try {
            $this->toolkit = $this->configureToolkit();
            $this->registerConfigurationAliases();
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot configure Toolkit.', 0, $e);
        }

        if ($request) {
            $this->toolkit->overrideService(
                'umi\http\Request',
                function () use ($request) {
                    return $request;
                }
            );
        }
    }

    /**
     * Инициализирует все необходимые данные для проекта
     * Если произошёл редирект и создан ответ, то инициализация останавливается
     * @return bool
     */
    public function init()
    {
        $this->createRequest();
        if ($this->response) {
            return false;
        }
        $this->initRoute();
        $this->initDomainUrl();
        $this->initProjectPrefix();
        $this->initSiteUrlPostfix();
        $this->initRoutePath();
        $this->initUrlManager();
        $this->initDispatcher();

        return true;
    }

    /**
     * Обрабатывает запрос и устанавливает ответ
     */
    public function dispatch()
    {
        $this->response = $this->dispatcher->dispatch($this->routePath ? : '/', $this->projectPrefix);
    }

    /**
     * Возвращает тулкит.
     * @return IToolkit
     */
    public function getToolkit()
    {
        return $this->toolkit;
    }

    /**
     * Возвращает директорию текущего проекта
     * @return string
     */
    public function getProjectDirectory()
    {
        return $this->projectDirectory;
    }

    /**
     * Возвращает директорию дампа данных проекта
     * @return string
     */
    public function getProjectDumpDirectory()
    {
        return $this->projectDumpDirectory;
    }

    /**
     * Возвращает имя текущего проекта
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Возвращает конфигурацию текущего проекта
     * @return array
     */
    public function getProjectConfig()
    {
        return $this->configToArray($this->projectConfig);
    }

    /**
     * Производит предварительную маршрутизацию для определения текущего проекта.
     * @throws RuntimeException
     * @throws UnexpectedValueException
     * @throws HttpNotFound
     * @return IRouteResult
     */
    public function dispatchProject()
    {
        /**
         * @var Request $request
         */
        $request = $this->toolkit->getService('umi\http\Request');

        $fileName = Environment::$directoryRoot . '/configuration/projects.config.php';
        if (!is_file($fileName)) {
            throw new RuntimeException(sprintf(
                'Projects configuration file "%s" does not exist.',
                $fileName
            ));
        }

        $projectsConfig = $this->loadConfig($fileName);
        /**
         * @var IRouteFactory $routeFactory
         */
        $routeFactory = $this->toolkit->getService('umi\route\IRouteFactory');

        $routeResult = null;
        $router = null;

        foreach ($projectsConfig as $projectName => $projectConfig) {
            if (!is_array($projectConfig)) {
                throw new UnexpectedValueException(sprintf(
                    'Configuration for project "%s" should be an array.',
                    $projectName
                ));
            }

            if (!isset($projectConfig['routes']) || !is_array($projectConfig['routes'])) {
                throw new UnexpectedValueException(sprintf(
                    'Routes configuration for project "%s" should be an array.',
                    $projectName
                ));
            }

            $routes = $projectConfig['routes'];
            $router = $routeFactory->createRouter($routes);

            $route = $request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo();

            $routeResult = $router->match($route);
            $routeMatches = $routeResult->getMatches();

            if (!empty($routeMatches)) {
                $this->projectName = $projectName;
                break;
            }
        }

        if (empty($routeMatches)) {
            throw new HttpNotFound('Project not found');
        }

        if (!isset($projectConfig['destination'])) {
            $projectConfig['destination'] = '~/' . $this->projectName;
        }

        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');

        $destinationDir = $configIO->getFilesByAlias($projectConfig['destination']);
        if (!isset($destinationDir[1])) {
            throw new UnexpectedValueException('Cannot resolve project destination.');
        }
        $this->projectDirectory = $destinationDir[1];

        $configIO->registerAlias(
            '~/project',
            __DIR__,
            $destinationDir[1]
        );

        if (!isset($projectConfig['dumpDestination'])) {
            $projectConfig['dumpDestination'] = '~/project/dump';
        }

        $dumpDir = $configIO->getFilesByAlias($projectConfig['dumpDestination']);
        if (!isset($dumpDir[1])) {
            throw new UnexpectedValueException('Cannot resolve project dump destination.');
        }
        $this->projectDumpDirectory = $dumpDir[1];

        if (!isset($projectConfig['componentConfig'])) {
            $projectConfig['componentConfig'] = '~/project/configuration/component.config.php';
        }

        $this->registerProjectComponentConfiguration($projectConfig['componentConfig']);
        $this->registerProjectTools();
        $this->configureLocalesService($router, $routeMatches);
        $this->registerProjectEventHandlers();
        $this->registerProjectAutoload();

        /**
         * @var IUrlManager $urlManager
         */
        $urlManager = $this->toolkit->getService('umicms\hmvc\url\IUrlManager');
        if (!isset($projectConfig['assetsUrl'])) {
            $projectConfig['assetsUrl'] = Environment::$baseUrl . '/' . $this->projectName . '/asset/';
        }
        $urlManager->setProjectAssetsUrl($projectConfig['assetsUrl']);

        if (!isset($projectConfig['adminAssetsUrl'])) {
            $projectConfig['adminAssetsUrl'] = Environment::$baseUrl . '/umi-admin/';
        }

        if (!isset($projectConfig['assetsDir'])) {
            $projectConfig['assetsDir'] = '~/project/asset/';
        }
        $assetsDir = $configIO->getFilesByAlias($projectConfig['assetsDir']);
        if (!isset($assetsDir[1])) {
            throw new UnexpectedValueException('Cannot resolve project dump destination.');
        }
        Environment::$directoryAssets = $assetsDir[1];

        $urlManager->setProjectAssetsUrl($projectConfig['assetsUrl']);

        $urlManager->setAdminAssetsUrl($projectConfig['adminAssetsUrl']);

        return $routeResult;
    }

    /**
     * Возвращает сформированный ответ
     * @return Response
     */
    public function getResponse()
    {
        $this->setUmiHeaders($this->response);

        if (!$this->response->headers->has(
                'content-type'
            ) && isset($this->allowedRequestFormats[$this->request->getRequestFormat()])
        ) {
            $this->response->headers->set(
                'content-type',
                $this->allowedRequestFormats[$this->request->getRequestFormat()]
            );
        }

        if (Environment::$browserCacheEnabled) {
            $this->setBrowserCacheHeaders($this->request, $this->response);
        }

        $this->response->prepare($this->request);

        if ($this->debugMode) {
            $this->response->getContent();
            return $this->createDebugResponse();
        }

        return $this->response;
    }

    /**
     * Устанавливает ETag для браузерного кэширования страниц.
     * @param Request $request
     * @param Response $response
     */
    protected function setBrowserCacheHeaders(Request $request, Response $response)
    {
        $response->setETag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);
    }

    /**
     * Создает компонент проекта.
     * @return IComponent
     */
    protected function createProject()
    {
        $config = $this->configToArray($this->projectConfig);

        /**
         * @var IMvcEntityFactory $mvcEntityFactory
         */
        $mvcEntityFactory = $this->toolkit->getService('umi\hmvc\IMvcEntityFactory');

        return $mvcEntityFactory->createComponent('project', 'project', $config);
    }

    /**
     * Предварительно обрабатывает Request, проверяет необходимость редиректов для SEO
     * и выполняет их.
     */
    protected function prepareRequest()
    {
        if ($this->request->query->has('UMI_DEBUG')) {
            $this->debugMode = true;
            $this->request->query->remove('UMI_DEBUG');
        }

        $pathInfo = $this->request->getPathInfo();
        $requestedUri = $this->request->getRequestUri();
        $queryString = $this->request->getQueryString();

        if (
            ($pathInfo != '/' && substr($pathInfo, -1, 1) == '/') ||
            ((substr($requestedUri, -1, 1) == '?') && !$queryString) ||
            substr($this->request->getSchemeAndHttpHost(), -1, 1) == '.'
        ) {

            $url = rtrim($pathInfo, '/');
            if ($queryString) {
                $url .= '?' . $queryString;
            }
            $host = rtrim($this->request->getSchemeAndHttpHost(), '.');

            $redirectLocation = $host . $url;

            $this->createMovedPermanentlyResponse($redirectLocation);
        }
    }

    /**
     * Создает и конфигурирует контейнер сервисов.
     * @throws RuntimeException
     * @return IToolkit
     */
    protected function configureToolkit()
    {
        $toolkit = new Toolkit();

        $masterConfig = $this->loadConfig(CMS_DIR . '/boot.config.php');

        if (isset($masterConfig[self::OPTION_TOOLS])) {
            $toolkit->registerToolboxes($masterConfig[self::OPTION_TOOLS]);
        }

        if (isset($masterConfig[self::OPTION_TOOLS_SETTINGS])) {
            $toolkit->setSettings($masterConfig[self::OPTION_TOOLS_SETTINGS]);
        }

        if (Environment::$bootConfig && is_file(Environment::$bootConfig)) {
            $localConfig = $this->loadConfig(Environment::$bootConfig);
            if (isset($localConfig[self::OPTION_TOOLS])) {
                $toolkit->registerToolboxes($localConfig[self::OPTION_TOOLS]);
            }

            if (isset($localConfig[self::OPTION_TOOLS_SETTINGS])) {
                $toolkit->setSettings($localConfig[self::OPTION_TOOLS_SETTINGS]);
            }
        }

        return $toolkit;
    }

    /**
     * Задает инициализаторы для добавления расширений в шаблонизаторы.
     */
    protected function initTemplateEngines()
    {
        /**
         * @var ITemplateEngineFactory $templateEngineFactory
         */
        $templateEngineFactory = $this->toolkit->getService('umi\templating\engine\ITemplateEngineFactory');

        $templateEngineFactory->setInitializer(
            ITemplateEngineFactory::PHP_ENGINE,
            function (PhpTemplateEngine $templateEngine) {

                $viewExtension = new ViewPhpExtension($this->toolkit);
                $templateExtension = new TemplatingPhpExtension($this->toolkit);

                $templateEngine
                    ->addExtension($viewExtension)
                    ->addExtension($templateExtension);
            }
        );

        $templateEngineFactory->setInitializer(
            TwigTemplateEngine::NAME,
            function (TwigTemplateEngine $templateEngine) {

                $viewExtension = new ViewTwigExtension($this->toolkit);
                $templateExtension = new TemplatingTwigExtension($this->toolkit);

                $templateEngine
                    ->addExtension($viewExtension)
                    ->addExtension($templateExtension);
            }
        );

        XsltTemplateEngine::unregisterStreams();
    }

    /**
     * Регистрирует алиасы для путей конфигураций.
     */
    protected function registerConfigurationAliases()
    {
        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');

        $configIO->registerAlias(
            '~',
            CMS_DIR,
            Environment::$directoryPublic
        );

        $configIO->registerAlias(
            '~/common',
            CMS_DIR,
            Environment::$directoryPublic . '/common'
        );

    }

    /**
     * Регистрирует интерфейс для использования конфигурации сайта.
     * @param string $configFileName имя файла
     * @return void
     */
    protected function registerProjectComponentConfiguration($configFileName)
    {
        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');
        $this->projectConfig = $configIO->read($configFileName);
    }

    /**
     * Конфигурирует URL-менеджер для ресурсов проекта.
     * @param IComponent $project проект
     */
    protected function configureAdminUrls(IComponent $project)
    {
        /**
         * @var IUrlManager $urlManager
         */
        $urlManager = $this->toolkit->getService('umicms\hmvc\url\IUrlManager');

        $urlManager->setAdminUrlPrefix(
            $project->getRouter()
                ->assemble('admin')
        );

        $adminComponent = $project->getChildComponent('admin');

        $urlManager->setRestUrlPrefix(
            $adminComponent->getRouter()
                ->assemble('rest')
        );
    }

    /**
     * Регистрирует сервисы для текущего проекта.
     * @return void
     */
    protected function registerProjectTools()
    {
        if ($this->projectConfig->has(self::OPTION_TOOLS)) {
            $this->toolkit->registerToolboxes(
                $this->projectConfig->get(self::OPTION_TOOLS)
            );
        }

        if ($this->projectConfig->has(self::OPTION_TOOLS_SETTINGS)) {
            $this->toolkit->setSettings(
                $this->projectConfig->get(self::OPTION_TOOLS_SETTINGS)
            );
        }

        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');
        $projectSettings = $configIO->read('~/project/configuration/project.config.php');

        $this->getToolkit()
            ->registerAwareInterface(
            'umicms\project\IProjectSettingsAware',
            function ($object) use ($projectSettings) {
                if ($object instanceof IProjectSettingsAware) {
                    $object->setProjectSettings($projectSettings);
                }
            }
        );
    }

    /**
     * Выполняет редирект и завершает работу приложения.
     */
    protected function createMovedPermanentlyResponse($redirectLocation)
    {
        /**
         * @var Response $response
         */
        $this->response = $this->toolkit->getService('umi\http\Response');
        $this->response->setStatusCode(Response::HTTP_MOVED_PERMANENTLY)
            ->headers->set('Location', $redirectLocation);
    }

    /**
     * Загружает конфигурацию.
     * @param string $filePath
     * @throws UnexpectedValueException
     * @return array
     */
    private function loadConfig($filePath)
    {
        /** @noinspection PhpIncludeInspection */
        $config = require($filePath);

        if (!is_array($config)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Configuration file "%s" should return an array.',
                    $filePath
                )
            );
        }

        return $config;
    }

    /**
     * Выставляет заголовки UMI.CMS.
     * @param Response $response
     */
    private function setUmiHeaders(Response $response)
    {
        $response->headers->set('X-Generated-By', 'UMI.CMS');
        $response->headers->set('X-Memory-Usage', round(memory_get_usage(true) / 1048576, 2) . ' Mib');
        if (Environment::$startTime > 0) {
            $response->headers->set('X-Generation-Time', round(microtime(true) - Environment::$startTime, 3));
        }
        Environment::$startTime = microtime(true);
    }

    /**
     * Подключает автолоадер для проекта, а так же регистрирует пространство имен project
     */
    private function registerProjectAutoload()
    {
        if (is_file($this->projectDirectory . '/autoload.php')) {
            /** @noinspection PhpIncludeInspection */
            require $this->projectDirectory . '/autoload.php';
        }
        Environment::$classLoader->addPsr4('project\\', $this->projectDirectory);
    }

    /**
     * Конфигурирует локали проекта
     * @param IRouter $router маршрутизатор проекта
     * @param array $routeMatches параметры маршрутизации до проекта
     * @throws \umicms\exception\UnexpectedValueException
     */
    protected function configureLocalesService(IRouter $router, array $routeMatches)
    {
        /**
         * @var CmsLocalesService $localesService
         */
        $localesService = $this->toolkit->getService('umi\i18n\ILocalesService');
        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');

        $localesConfig = $this->configToArray($configIO->read('~/project/configuration/locales.config.php'), true);

        if (isset($localesConfig['site'])) {
            $siteLocalesConfig = $localesConfig['site'];
            if (!is_array($siteLocalesConfig)) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot configure site locales for project "%s". Locales configuration should be an array.',
                    $this->projectName
                ));
            }

            $siteLocales = [];

            foreach ($siteLocalesConfig as $localeId => $localeConfig) {

                if (!isset($localeConfig['route'])) {
                    throw new UnexpectedValueException(sprintf(
                        'Cannot configure site locales for project "%s". Locale "%s" configuration should contain "route" option.',
                        $this->projectName,
                        $localeId
                    ));
                }

                $sileLocale = new SiteLocale($localeId);
                $sileLocale->setUrl($router->assemble($localeConfig['route']));

                $siteLocales[] = $sileLocale;
            }

            $localesService->setSiteLocales($siteLocales);
        }

        if (isset($localesConfig['admin'])) {
            $adminLocalesConfig = $localesConfig['admin'];
            if (!is_array($adminLocalesConfig)) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot configure admin locales for project "%s". Locales configuration should be an array.',
                    $this->projectName
                ));
            }

            $adminLocales = [];

            foreach ($adminLocalesConfig as $localeId => $localeConfig) {
                $adminLocales[] = new AdminLocale($localeId);
            }

            $localesService->setAdminLocales($adminLocales);
        }

        if (isset($routeMatches['locale'])) {
            $localesService->setCurrentLocale($routeMatches['locale']);
            $localesService->setCurrentDataLocale($routeMatches['locale']);
        }

        if (isset($localesConfig['defaultSiteLocale'])) {
            $localesService->setDefaultLocale($localesConfig['defaultSiteLocale']);
            $localesService->setDefaultDataLocale($localesConfig['defaultSiteLocale']);
            $localesService->setDefaultSiteLocaleId($localesConfig['defaultSiteLocale']);
        }

        if (isset($localesConfig['defaultAdminLocale'])) {
            $localesService->setDefaultAdminLocaleId($localesConfig['defaultAdminLocale']);
        }
    }

    /**
     * Регистрирует обработчики событий для проекта
     * @throws UnexpectedValueException
     */
    protected function registerProjectEventHandlers()
    {
        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');
        $eventHandlers = $this->configToArray(
            $configIO->read('~/project/configuration/eventHandlers.config.php'),
            true
        );

        foreach ($eventHandlers as $handlerClass => $eventInfo) {
            if (!isset($eventInfo['type'])) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot configure events for project "%s". Handler "%s" configuration should contain "type" option.',
                    $this->projectName,
                    $handlerClass
                ));
            }

            $tags = (isset($eventInfo['tags']) && is_array($eventInfo['tags'])) ? $eventInfo['tags'] : null;

            $this->toolkit->bindEvent(
                $eventInfo['type'],
                function (IEvent $event) use ($handlerClass) {
                    if (!isset($handlerInstances[$handlerClass])) {
                        $handler = $this->toolkit->getPrototype($handlerClass)
                            ->createSingleInstance();
                        if (is_callable($handler)) {
                            $handler($event);
                        }
                    }
                },
                $tags
            );
        }
    }

    /**
     * Инициализирует запрос
     */
    protected function createRequest()
    {
        $this->request = $this->toolkit->getService('umi\http\Request');

        $this->prepareRequest($this->request);
    }

    /**
     * Инициализирует менеджер адресации
     */
    protected function initUrlManager()
    {
        $this->project = $this->createProject();
        /**
         * @var IUrlManager $urlManager
         */
        $urlManager = $this->toolkit->getService('umicms\hmvc\url\IUrlManager');
        $urlManager->setSchemeAndHttpHost($this->domainUrl);
        $urlManager->setUrlPrefix($this->projectPrefix);
        $urlManager->setSiteUrlPostfix($this->siteUrlPostfix);

        $this->configureAdminUrls($this->project);
    }

    /**
     * Инициализирует диспетчер
     */
    protected function initDispatcher()
    {
        $this->dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
        $this->initTemplateEngines();
        $this->dispatcher->setCurrentRequest($this->request);
        $this->dispatcher->setInitialComponent($this->project);
    }

    /**
     * Инициализирует префикс проекта
     */
    protected function initProjectPrefix()
    {
        $routeMatches = $this->route->getMatches();
        $this->projectPrefix = isset($routeMatches['prefix']) ? $routeMatches['prefix'] : '';
    }

    /**
     * Инициализирует роутинг
     */
    protected function initRoute()
    {
        $this->route = $this->dispatchProject();
    }

    /**
     * Инициализирует домен проекта
     */
    protected function initDomainUrl()
    {
        $routeMatches = $this->route->getMatches();
        $this->domainUrl = $routeMatches[ProjectHostRoute::OPTION_SCHEME] . '://' . $routeMatches[ProjectHostRoute::OPTION_HOST];
        if (80 != $routeMatches[ProjectHostRoute::OPTION_PORT]) {
            $this->domainUrl .= ':' . $routeMatches[ProjectHostRoute::OPTION_PORT];
        }
    }

    /**
     * Инициализирует суффикс проекта
     */
    protected function initSiteUrlPostfix()
    {
        $routeMatches = $this->route->getMatches();
        $this->siteUrlPostfix = isset($routeMatches['postfix']) ? $routeMatches['postfix'] : null;
    }

    /**
     * Инициализирует путь роутинг проекта
     */
    protected function initRoutePath()
    {
        $routePath = $this->route->getUnmatchedUrl();

        if (preg_match('|\.([\w]+)$|u', $routePath, $matches)) {
            $format = $matches[1];

            if ($format === $this->siteUrlPostfix || isset($this->allowedRequestFormats[$format])) {
                $routePath = substr($routePath, 0, -strlen($format) - 1);

                if ($format === $this->siteUrlPostfix) {
                    $format = 'html';
                    $this->allowedRequestFormats['html'] = 'text/html; charset=utf8';
                }

                $this->request->setRequestFormat($format);
            }
        }
        $this->routePath = $routePath;
    }

    private function createDebugResponse()
    {
        $response = new Response();
        $objects = $this->getObjectByCollection();
        $content = ['collections' => []];
        foreach ($objects as $collectionName => $collectionObjects) {
            $current = [];
            $current['name'] = $collectionName;
            $current['objects'] = [];
            foreach ($collectionObjects as $object) {
                $current['objects'][] = $object;
            }
            $content['collections'][] = $current;
        }

        $response->headers->set('Content-Type', $this->allowedRequestFormats['json']);
        $response->setContent(json_encode($content));
        return $response;
    }

    /**
     * @return mixed
     */
    private function getObjectByCollection()
    {
        /** @var IObjectManager $manager */
        $manager = $this->getToolkit()->getService('umi\orm\manager\IObjectManager');
        $groupedObjects = [];
        foreach ($manager->getObjects() as $object) {
            $groupedObjects[$object->getCollectionName()][] = ['id' => $object->getId(), 'typeName' => $object->getTypeName()];
        }
        return $groupedObjects;
    }

}
