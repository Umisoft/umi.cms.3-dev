<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms;

use umi\config\entity\IConfig;
use umi\config\io\IConfigIO;
use umi\extension\twig\TemplatingTwigExtension;
use umi\extension\twig\TwigTemplateEngine;
use umi\hmvc\component\IComponent;
use umi\hmvc\dispatcher\IDispatcher;
use umi\hmvc\IMvcEntityFactory;
use umi\http\Request;
use umi\http\Response;
use umi\i18n\ILocalesService;
use umi\route\IRouteFactory;
use umi\route\result\IRouteResult;
use umi\spl\config\TConfigSupport;
use umi\templating\engine\ITemplateEngineFactory;
use umi\templating\engine\php\PhpTemplateEngine;
use umi\templating\engine\php\TemplatingPhpExtension;
use umi\toolkit\IToolkit;
use umi\toolkit\Toolkit;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\url\IUrlManager;
use umicms\project\config\IProjectConfigAware;
use umicms\project\config\TProjectConfigAware;
use umicms\templating\engine\php\ViewPhpExtension;
use umicms\templating\engine\twig\ViewTwigExtension;

/**
 * Загрузчик приложений UMI.CMS
 */
class Bootstrap implements IProjectConfigAware
{
    use TConfigSupport;
    use TProjectConfigAware;

    /**
     * Опция конфигурации для регистрации инструментов
     */
    const OPTION_TOOLS = 'toolkit';
    /**
     * Конфигурация иснтрументов
     */
    const OPTION_TOOLS_SETTINGS = 'settings';

    /**
     * Тип контента в зависимости от формата запроса.
     * @var array $contentTypes
     */
    public static $contentTypes = [
        'html' => 'text/html; charset=utf8',
        'json' => 'application/json; charset=utf8',
        'xml' => 'text/xml; charset=utf8',
    ];

    /**
     * @var Environment $environment настройки окружения UMI.CMS
     */
    protected $environment;
    /**
     * @var Toolkit $toolkit контейнер сервисов
     */
    protected $toolkit;

    /**
     * @var IConfig $config конфигурация
     */
    protected $config;

    /**
     * Конструктор.
     * @param Environment $environment настройки окружения UMI.CMS
     * @throws RuntimeException если не удалось сконфигурировать сервисы
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;

        try {
            $this->toolkit = $this->configureToolkit();
            $this->registerConfigurationAliases();
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot configure Toolkit.', 0, $e);
        }
    }

    /**
     * Выполняет загрузчик.
     */
    public function run()
    {
        /**
         * @var Request $request
         */
        $request = $this->toolkit->getService('umi\http\Request');
        $this->prepareRequest($request);

        $routeResult = $this->routeProject($request);
        $routeMatches = $routeResult->getMatches();

        $project = $this->createProject();

        if (isset($routeMatches['locale'])) {
            /**
             * @var ILocalesService $localesService
             */
            $localesService = $this->toolkit->getService('umi\i18n\ILocalesService');
            $localesService->setCurrentLocale($routeMatches['locale']);
        }

        $baseProjectUrl = isset($routeMatches['uri']) ? $routeMatches['uri'] : '';
        $routePath = $routeResult->getUnmatchedUrl() ? : '/';

        $this->configureUrlManager($project, $routeResult, $baseProjectUrl);

        if (preg_match('|\.([\w]+)$|u', $routePath, $matches)) {
            $format = $matches[1];
            $routePath = substr($routePath, 0, -strlen($format) - 1);
            $request->setRequestFormat($format);
        }

        /**
         * @var IDispatcher $dispatcher
         */
        $dispatcher = $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
        $this->initTemplateEngines($dispatcher);
        $dispatcher->setCurrentRequest($request);
        $dispatcher->setInitialComponent($project);
        $response = $dispatcher->dispatch($routePath, $baseProjectUrl);

        $this->sendResponse($response, $request);
    }

    /**
     * Отправляет ответ.
     * @param Response $response
     * @param Request $request
     */
    protected function sendResponse(Response $response, Request $request)
    {
        $this->setUmiHeaders($response);

        $response->setETag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);

        if (!$response->headers->has('content-type') && isset(static::$contentTypes[$request->getRequestFormat()])) {
            $response->headers->set('content-type', static::$contentTypes[$request->getRequestFormat()]);
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
        global $umicmsStartTime;

        $response->headers->set('X-Generated-By', 'UMI.CMS');
        $response->headers->set('X-Memory-Usage', round(memory_get_usage(true) / 1048576, 2) . ' Mib');
        if ($umicmsStartTime > 0) {
            $response->headers->set('X-Generation-Time', round(microtime(true) - $umicmsStartTime, 3));
        }
    }

    /**
     * Создает компонент проекта.
     * @return IComponent
     */
    protected function createProject()
    {
        $config = $this->configToArray($this->getProjectConfig());

        /**
         * @var IMvcEntityFactory $mvcEntityFactory
         */
        $mvcEntityFactory = $this->toolkit->getService('umi\hmvc\IMvcEntityFactory');

        return $mvcEntityFactory->createComponent('project', 'project', $config);
    }

    /**
     * Производит предварительную маршрутизацию для определения конфигурации проекта.
     * @param Request $request
     * @throws RuntimeException
     * @throws UnexpectedValueException
     * @return IRouteResult результат маршрутизации
     */
    protected function routeProject(Request $request)
    {
        if (!is_file($this->environment->projectConfiguration)) {
            throw new RuntimeException(sprintf(
                'Projects configuration file "%s" does not exist.',
                $this->environment->projectConfiguration
            ));
        }

        $siteRoutes = $this->loadConfig($this->environment->projectConfiguration);

        /**
         * @var IRouteFactory $routeFactory
         */
        $routeFactory = $this->toolkit->getService('umi\route\IRouteFactory');
        $siteRouter = $routeFactory->createRouter($siteRoutes);

        $route = $request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo();
        $routeResult = $siteRouter->match($route);
        $routeMatches = $routeResult->getMatches();

        if (empty($routeMatches)) {
            $this->send404('Project not found.');
        }

        $destination = $this->getRequiredOption(
            $routeMatches,
            'destination',
            function () {
                throw new InvalidArgumentException(sprintf(
                    'Cannot route project. Option "destination" required in "%s".',
                    $this->environment->projectConfiguration
                ));
            }
        );

        $configFileName = $this->getRequiredOption(
            $routeMatches,
            'config',
            function () {
                throw new InvalidArgumentException(sprintf(
                    'Cannot route project. Option "config" required in "%s".',
                    $this->environment->projectConfiguration
                ));
            }
        );

        /**
         * @var IConfigIO $configIO
         */
        $configIO = $this->toolkit->getService('umi\config\io\IConfigIO');

        $directories = $configIO->getFilesByAlias($destination);
        if (!isset($directories[1])) {
            throw new UnexpectedValueException('Cannot resolve project destination.');
        }

        $configIO->registerAlias(
            '~/project',
            $this->environment->directoryCmsProject,
            $directories[1]
        );

        $this->registerProjectConfiguration($configFileName);
        $this->registerProjectTools();

        return $routeResult;
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

            /**
             * @var Response $response
             */
            $response = $this->toolkit->getService('umi\http\Response');
            $response->setStatusCode(Response::HTTP_MOVED_PERMANENTLY)
                ->headers->set('Location', $redirectLocation);

            $response->send();
            exit();
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

        if (!is_file($this->environment->bootConfigMaster)) {
            throw new RuntimeException(sprintf(
                'Boot configuration file "%s" does not exist.',
                $this->environment->bootConfigMaster
            ));
        }

        $masterConfig = $this->loadConfig($this->environment->bootConfigMaster);

        if (isset($masterConfig[self::OPTION_TOOLS])) {
            $toolkit->registerToolboxes($masterConfig[self::OPTION_TOOLS]);
        }

        if (isset($masterConfig[self::OPTION_TOOLS_SETTINGS])) {
            $toolkit->setSettings($masterConfig[self::OPTION_TOOLS_SETTINGS]);
        }

        if (is_file($this->environment->bootConfigLocal)) {
            $localConfig = $this->loadConfig($this->environment->bootConfigLocal);
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
     * Задает инициализаторы для добавления расширений в шаблонизаторы
     * @param IDispatcher $dispatcher
     */
    protected function initTemplateEngines(IDispatcher $dispatcher)
    {
        /**
         * @var ITemplateEngineFactory $templateEngineFactory
         */
        $templateEngineFactory = $this->toolkit->getService('umi\templating\engine\ITemplateEngineFactory');
        $templateEngineFactory->setInitializer(
            ITemplateEngineFactory::PHP_ENGINE,
            function (PhpTemplateEngine $templateEngine) use ($dispatcher) {

                $viewExtension = new ViewPhpExtension($dispatcher);
                $templateExtension = new TemplatingPhpExtension();

                $templateEngine
                    ->addExtension($viewExtension)
                    ->addExtension($templateExtension);
            }
        );

        $templateEngineFactory->setInitializer(
            TwigTemplateEngine::NAME,
            function (TwigTemplateEngine $templateEngine) use ($dispatcher) {

                $viewExtension = new ViewTwigExtension($dispatcher);
                $templateExtension = new TemplatingTwigExtension();

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
            $this->environment->directoryCms,
            $this->environment->directoryProjects
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
        $this->setProjectConfig($configIO->read($configFileName));

        $this->toolkit->registerAwareInterface(
            'umicms\project\config\IProjectConfigAware',
            function ($object) {
                if ($object instanceof IProjectConfigAware) {
                    $object->setProjectConfig($this->getProjectConfig());
                }
            }
        );
    }

    /**
     * Конфигурирует URL-менеджер для проекта.
     * @param IComponent $project проект
     * @param IRouteResult $routeResult результат маршрутизации до проекта
     * @param string $baseProjectUrl базовый URL проекта
     */
    protected function configureUrlManager(IComponent $project, IRouteResult $routeResult, $baseProjectUrl)
    {
        /**
         * @var IUrlManager $urlManager
         */
        $urlManager = $this->toolkit->getService('umicms\hmvc\url\IUrlManager');
        $domainUrl = substr($routeResult->getMatchedUrl(), 0, -strlen($baseProjectUrl));
        $urlManager->setProjectDomainUrl($domainUrl);
        $urlManager->setBaseUrl($baseProjectUrl);

        $baseAdminUrl = $baseProjectUrl . $project->getRouter()
                ->assemble('admin');
        $adminComponent = $project->getChildComponent('admin');

        $urlManager->setBaseAdminUrl($baseAdminUrl);
        $urlManager->setBaseRestUrl(
            $baseAdminUrl . $adminComponent->getRouter()
                ->assemble('api')
        );
        $urlManager->setBaseSettingsUrl(
            $baseAdminUrl . $adminComponent->getRouter()
                ->assemble('settings')
        );
    }

    /**
     * Регистрирует сервисы для текущего проекта.
     * @return void
     */
    protected function registerProjectTools()
    {
        $config = $this->getProjectConfig();
        if ($config->has(self::OPTION_TOOLS)) {
            $this->toolkit->registerToolboxes(
                $config->get(self::OPTION_TOOLS)
            );
        }

        if ($config->has(self::OPTION_TOOLS_SETTINGS)) {
            $this->toolkit->setSettings(
                $config->get(self::OPTION_TOOLS_SETTINGS)
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

}
