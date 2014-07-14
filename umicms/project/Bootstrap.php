<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project;

use umi\config\entity\IConfig;
use umi\config\io\IConfigIO;
use umi\extension\twig\TwigTemplateEngine;
use umi\hmvc\component\IComponent;
use umi\hmvc\IMvcEntityFactory;
use umi\http\Request;
use umi\http\Response;
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

/**
 * Загрузчик проектов UMI.CMS
 */
class Bootstrap
{
    use TConfigSupport;

    /**
     * Текущая версия UMI.CMS
     */
    const VERSION = '%version%';
    /**
     * Дата выпуска текущей версии UMI.CMS
     */
    const VERSION_DATE = '%versionDate%';

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
        'xml' => 'text/xml; charset=utf8',
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
     * @var string $projectDestination возвращает директорию проекта
     */
    protected $projectDirectory = '.';

    /**
     * Конструктор.
     * @throws RuntimeException если не удалось сконфигурировать сервисы
     */
    public function __construct()
    {
        try {
            $this->toolkit = $this->configureToolkit();
            $this->registerConfigurationAliases();
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot configure Toolkit.', 0, $e);
        }
    }

    /**
     * Выполняет запрос
     */
    public function run()
    {
        /**
         * @var Request $request
         */
        $request = $this->toolkit->getService('umi\http\Request');
        $this->prepareRequest($request);

        $routeResult = $this->routeProject();
        $routeMatches = $routeResult->getMatches();

        $projectPrefix = isset($routeMatches['prefix']) ? $routeMatches['prefix'] : '';
        $siteUrlPostfix = isset($routeMatches['postfix']) ? $routeMatches['postfix'] : null;
        $domainUrl = $routeMatches[ProjectHostRoute::OPTION_SCHEME] . '://' . $routeMatches[ProjectHostRoute::OPTION_HOST];
        $routePath = $routeResult->getUnmatchedUrl();

        if (preg_match('|\.([\w]+)$|u', $routePath, $matches)) {
            $format = $matches[1];

            if ($format === $siteUrlPostfix || isset($this->allowedRequestFormats[$format])) {
                $routePath = substr($routePath, 0, -strlen($format) - 1);

                if ($format === $siteUrlPostfix) {
                    $format = 'html';
                    $this->allowedRequestFormats['html'] = 'text/html; charset=utf8';
                }

                $request->setRequestFormat($format);
            }
        }

        $routePath = $routePath ?: '/';

        $project = $this->createProject();
        /**
         * @var IUrlManager $urlManager
         */
        $urlManager = $this->toolkit->getService('umicms\hmvc\url\IUrlManager');

        $urlManager->setSchemeAndHttpHost($domainUrl);
        $urlManager->setUrlPrefix($projectPrefix);
        $urlManager->setSiteUrlPostfix($siteUrlPostfix);

        $this->configureAdminUrls($project);

        /**
         * @var CmsDispatcher $dispatcher
         */
        $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
        $this->initTemplateEngines();
        $dispatcher->setCurrentRequest($request);
        $dispatcher->setInitialComponent($project);
        $response = $dispatcher->dispatch($routePath, $projectPrefix);

        $this->sendResponse($response, $request);
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
     * Производит предварительную маршрутизацию для определения текущего проекта.
     * @throws RuntimeException
     * @throws UnexpectedValueException
     * @return IRouteResult
     */
    public function routeProject()
    {
        /**
         * @var Request $request
         */
        $request = $this->toolkit->getService('umi\http\Request');

        $fileName = Environment::$directoryProjects . '/configuration/projects.config.php';
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

        $projectName = '';
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
                break;
            }
        }

        if (empty($routeMatches)) {
            $this->send404('Project not found.');
            exit();
        }

        if (!isset($projectConfig['destination'])) {
            throw new UnexpectedValueException(sprintf(
                'Cannot route project "%s". Option "destination" required.',
                $projectName
            ));
        }

        if (!isset($projectConfig['config'])) {
            throw new UnexpectedValueException(sprintf(
                'Cannot route project "%s". Option "config" required.',
                $projectName
            ));
        }

        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');

        $directories = $configIO->getFilesByAlias($projectConfig['destination']);
        if (!isset($directories[1])) {
            throw new UnexpectedValueException('Cannot resolve project destination.');
        }
        $this->projectDirectory = $directories[1];

        $configIO->registerAlias(
            '~/project',
            __DIR__,
            $directories[1]
        );

        $this->registerProjectConfiguration($projectConfig['config']);
        $this->registerProjectTools();
        $this->configureLocalesService($projectName, $router, $routeMatches);

        return $routeResult;
    }

    /**
     * Отправляет ответ.
     * @param Response $response
     * @param Request $request
     */
    protected function sendResponse(Response $response, Request $request)
    {
        $this->setUmiHeaders($response);

        if (!$response->headers->has('content-type') && isset($this->allowedRequestFormats[$request->getRequestFormat()])) {
            $response->headers->set('content-type', $this->allowedRequestFormats[$request->getRequestFormat()]);
        }

        $response->prepare($request)
            ->send();
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
     * @param Request $request
     */
    protected function prepareRequest(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $requestedUri = $request->getRequestUri();
        $queryString = $request->getQueryString();

        if (
            ($pathInfo != '/' && substr($pathInfo, -1, 1) == '/') ||
            (substr($requestedUri, -1, 1) == '?') && !$queryString)
        {

            $url = rtrim($pathInfo, '/');
            if ($queryString) {
                $url .= '?' . $queryString;
            }
            $redirectLocation = $request->getSchemeAndHttpHost() . $url;

            $this->send301($redirectLocation);
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

        $masterConfig = $this->loadConfig(CMS_DIR . '/configuration/boot.config.php');

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
            Environment::$directoryProjects
        );

    }

    /**
     * Регистрирует интерфейс для использования конфигурации сайта.
     * @param string $configFileName имя файла
     * @return void
     */
    protected function registerProjectConfiguration($configFileName)
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

        $urlManager->setAdminUrlPrefix($project->getRouter()->assemble('admin'));

        $adminComponent = $project->getChildComponent('admin');

        $urlManager->setRestUrlPrefix($adminComponent->getRouter()->assemble('rest'));
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
    }

    /**
     * Отправляет статус 404 и завершает работу приложения.
     */
    protected function send404($content)
    {
        /**
         * @var Response $response
         */
        $response = $this->toolkit->getService('umi\http\Response');
        $response->setContent($content);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $response->send();
        exit();
    }

    /**
     * Выполняет редирект и завершает работу приложения.
     */
    protected function send301($redirectLocation)
    {
        /**
         * @var Response $response
         */
        $response = $this->toolkit->getService('umi\http\Response');
        $response->setStatusCode(Response::HTTP_MOVED_PERMANENTLY)
            ->headers->set('Location', $redirectLocation);

        $response->send();
        exit();
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
     * Конфигурирует локали проекта
     * @param string $projectName имя проекта
     * @param IRouter $router маршрутизатор проекта
     * @param array $routeMatches параметры маршрутизации до проекта
     * @throws UnexpectedValueException если конфигурация локалей невалидная
     */
    protected function configureLocalesService($projectName, IRouter $router, array $routeMatches)
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
                    $projectName
                ));
            }

            $siteLocales = [];

            foreach ($siteLocalesConfig as $localeId => $localeConfig) {

                if (!isset($localeConfig['route'])) {
                    throw new UnexpectedValueException(sprintf(
                        'Cannot configure site locales for project "%s". Locale "%s" configuration should contain "route" option.',
                        $projectName,
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
                    $projectName
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

}
