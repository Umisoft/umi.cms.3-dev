<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest;

use AspectMock\Test;
use Codeception\Lib\Framework;
use Codeception\TestCase;
use Codeception\Util\Debug;
use umi\dbal\cluster\IDbCluster;
use umi\dbal\toolbox\DbalTools;
use umi\http\Request;
use umi\toolkit\IToolkit;
use umicms\hmvc\url\IUrlManager;
use umicms\module\IModule;
use umicms\project\Bootstrap;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Модуль, расширяющий Codeception framework
 * для тестирования проектов UMI.CMS.
 */
class UmiModule extends Framework
{
    /**
     * @api
     * @var UmiConnector
     */
    public $client;

    /**
     * Локаль, для тестирования локализованных строк.
     * @var string $currentLocale
     */
    protected $locale;
    /**
     * Список плейсхолдеро, доступных текстах локализованных строк.
     * @var array $localePlaceholders
     */
    protected $localePlaceholders = [];
    /**
     * Префикс для всех адресов проекта
     * @var string $projectUrl
     */
    protected $projectUrlPrefix;
    /**
     * @var IToolkit $commonToolkit common toolkit.
     */
    protected $commonToolkit;
    /**
     * @var IDbCluster $commonDbCluster common db connection used for all requests.
     */
    protected $commonDbCluster;
    /**
     * @var MockMessageBox тестовый почтовый ящик
     */
    public $messageBox;

    /**
     * {@inheritdoc}
     */
    public function _initialize()
    {
        $this->locale = $this->config['locale'];
        $this->projectUrlPrefix = $this->config['projectUrl'];

        $this->initializeCommonToolkit();
        $this->initializeUrlMap();
        $this->initializePlaceholders();
    }

    /**
     * {@inheritdoc}
     */
    public function _before(TestCase $test)
    {
        $this->initializeMocks();
        $this->initializeMessageBox();

        $this->client = new UmiConnector();
        $this->client->setMessageBox($this->messageBox);
        $this->client->followRedirects(true);

        $this->injectCommonServices();
        $this->beginDbTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function _after(TestCase $test)
    {
        if ($this->grabDbCluster()->getConnection()->isTransactionActive()) {
            $this->rollbackDbTransaction();
        }
        Test::clean();
    }

    /**
     * Create new registered user. TestUser by default.
     * @param string|null $userName use as login, password and displayName
     * @return \umicms\project\module\users\model\object\RegisteredUser
     */
    public function haveRegisteredUser($userName = 'TestUser')
    {
        Debug::debug('Creating registered user "' . $userName  . "'");
        /**
         * @var RegisteredUser $user
         */
        $user = $this->grabUsersModule()
            ->user()
            ->add(RegisteredUser::TYPE_NAME);
        $user->login = $userName;
        $user->password = $userName;
        $user->displayName = $userName;
        $user->email = "{$userName}@example.com";

        $this->grabUsersModule()
            ->register($user);
        $user->active = true;

        $this->grabOrmObjectPersister()->commit();

        return $user;
    }

    /**
     * Grabs a service from common Toolkit
     * @param string $serviceInterfaceName
     * @param null|string $concreteClassName prepare realization
     * @return object
     */
    public function grabService($serviceInterfaceName, $concreteClassName = null)
    {
        return $this->commonToolkit->getService($serviceInterfaceName, $concreteClassName);
    }

    /**
     * Grabs ORM Object persister from common Toolkit
     * @return \umi\orm\persister\IObjectPersister
     */
    public function grabOrmObjectPersister()
    {
        return $this->grabService('umi\orm\persister\IObjectPersister');
    }

    /**
     * Grabs DbCluster persister from common Toolkit
     * @return IDbCluster
     */
    public function grabDbCluster()
    {
        return $this->commonDbCluster;
    }

    /**
     * Grabs a UMI.CMS module from common Toolkit
     * @param string $moduleClassName
     * @return IModule
     */
    public function grabModule($moduleClassName)
    {
        return $this->grabService('umicms\module\IModule', $moduleClassName);
    }

    /**
     * Grabs a UMI.CMS UsersModule from common Toolkit
     * @return UsersModule
     */
    public function grabUsersModule()
    {
        return $this->grabModule(UsersModule::className());
    }

    /**
     * Внедряет общие сервисы в Toolkit для каждого Request
     */
    protected function injectCommonServices()
    {
        $this->client->setToolkitInitializer(function(IToolkit $toolkit) {
            /**
             * @var DbalTools $dbalTools
             */
            $dbalTools = $toolkit->getToolbox(DbalTools::NAME);
            $dbalTools->setCluster($this->commonDbCluster);
        });
    }

    /**
     * Заменяет в url все вхождения placeholders
     * @param  $url
     * @return string
     */
    protected function replaceUrlPlaceholders($url)
    {
        return str_replace(array_keys($this->localePlaceholders), array_values($this->localePlaceholders), $url);
    }

    /**
     * Инициализирует карту URL для тестов
     */
    protected function initializeUrlMap()
    {
        $reflection = new \ReflectionClass('umitest\UrlMap');
        $defaultProperties = $reflection->getDefaultProperties();

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_STATIC) as $property) {
            if (isset($defaultProperties[$property->name])) {
                $property->setValue($this->projectUrlPrefix . $defaultProperties[$property->name]);
            }
        }

        /**
         * @var IUrlManager $urlManager
         */
        $urlManager = $this->grabService('umicms\hmvc\url\IUrlManager');

        UrlMap::$projectAbsoluteUrl = $urlManager->getSchemeAndHttpHost() . $this->projectUrlPrefix;
        UrlMap::$projectUrl = $this->projectUrlPrefix;
    }

    /**
     * Инициализирует плейсхолдеры для замены в локализованных сообщениях
     */
    protected function initializePlaceholders()
    {
        $this->localePlaceholders = [];
        foreach (get_class_vars('umitest\UrlMap') as $varName => $value) {
            $this->localePlaceholders['{' . $varName . '}'] = $value;
        }
    }

    /**
     * Start transaction for request
     */
    protected function beginDbTransaction()
    {
        $this->grabDbCluster()->getConnection()->beginTransaction();
    }

    /**
     * Rollback transaction
     */
    protected function rollbackDbTransaction()
    {
        Debug::debug('Rollback transaction');
        $this->grabDbCluster()->getConnection()->rollBack();
    }

    /**
     * Initialize common toolkit (for all tests)
     */
    protected function initializeCommonToolkit()
    {
        /**
         * @var Request $request
         */
        $request = Request::create($this->projectUrlPrefix);

        $bootstrap = new Bootstrap($request);
        $bootstrap->init();

        $this->commonToolkit = $bootstrap->getToolkit();
        $this->commonDbCluster = $this->commonToolkit->getService('umi\dbal\cluster\IDbCluster');
    }

    /**
     * Initialize new message box for test
     */
    protected function initializeMessageBox()
    {
        $this->messageBox = new MockMessageBox();
    }

    /**
     * Initialize mock for testing
     */
    protected function initializeMocks()
    {
        Test::double(
            'umicms\form\element\Captcha',
            [
                'validate' => function () {
                    return true;
                }
            ]
        );

        $that = $this;

        /** @noinspection PhpUnusedParameterInspection */
        Test::double(
            'umicms\hmvc\component\BaseCmsController',
            [
                'sendMail' => function ($subject, $body, $contentType, $files, $to, $from, $charset) use ($that) {
                    $that->messageBox->push($to, $subject, $body);
                }
            ]
        );

        Test::double(
            'umi\orm\persister\ObjectPersister',
             [
                 'startTransaction' => false,
                 'commitTransaction' => false,
                 'rollbackTransaction' => false
             ]
        );
    }

}
 