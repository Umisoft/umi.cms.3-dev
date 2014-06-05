<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace umicms\project\module\search\tests;

use RuntimeException;
use umi\dbal\toolbox\DbalTools;
use umi\orm\collection\CollectionManager;
use umi\orm\selector\Selector;
use umi\spl\config\TConfigSupport;
use umi\toolkit\IToolkit;
use umi\toolkit\Toolkit;
use umicms\project\Bootstrap;
use umicms\Environment;
use UnexpectedValueException;

/**
 * Class SearchTestCase
 */
class SearchTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var bool $backupGlobals
     */
    protected $backupGlobals = false;

    use TConfigSupport;

    /**
     * @var Environment $environment
     */
    protected $environment;
    /**
     * @var Toolkit $toolkit
     */
    protected $toolkit;

    /**
     * Создает и конфигурирует контейнер сервисов.
     * @throws \RuntimeException
     * @return IToolkit
     */
    public function setUp()
    {
        $projectRoot = realpath(__DIR__ . '/../../../../..');
        $this->environment = new Environment();
        $this->environment->bootConfigMaster = $projectRoot . '/umicms/configuration/boot.config.php';
        $this->environment->bootConfigLocal = $projectRoot . '/configuration/boot.config.php';
        $this->environment->projectConfiguration = $projectRoot . '/umicms/configuration/projects.config.php';
        $this->environment->directoryCms = $projectRoot . '/umicms';
        $this->environment->directoryCmsProject = $projectRoot . '/umicms/project';
        $this->environment->directoryProjects = $projectRoot;

        $toolkit = new Toolkit();

        if (!is_file($this->environment->bootConfigMaster)) {
            throw new RuntimeException(sprintf(
                'Boot configuration file "%s" does not exist.',
                $this->environment->bootConfigMaster
            ));
        }

        $masterConfig = $this->loadConfig($this->environment->bootConfigMaster);

        if (isset($masterConfig[Bootstrap::OPTION_TOOLS])) {
            $toolkit->registerToolboxes($masterConfig[Bootstrap::OPTION_TOOLS]);
        }

        if (isset($masterConfig[Bootstrap::OPTION_TOOLS_SETTINGS])) {
            $toolkit->setSettings($masterConfig[Bootstrap::OPTION_TOOLS_SETTINGS]);
        }

        if (is_file($this->environment->bootConfigLocal)) {
            $localConfig = $this->loadConfig($this->environment->bootConfigLocal);
            if (isset($localConfig[Bootstrap::OPTION_TOOLS])) {
                $toolkit->registerToolboxes($localConfig[Bootstrap::OPTION_TOOLS]);
            }

            if (isset($localConfig[Bootstrap::OPTION_TOOLS_SETTINGS])) {
                $toolkit->setSettings($localConfig[Bootstrap::OPTION_TOOLS_SETTINGS]);
            }
        }

        $toolkit->registerToolboxes(
            [
                require $projectRoot . '/vendor/umi/framework-dev/library/dbal/toolbox/config.php',
                require $projectRoot . '/vendor/umi/framework-dev/library/stemming/toolbox/config.php',
            ]
        );
        $toolkit->setSettings(
            [
                DbalTools::NAME => [
                    'servers' => [
                        [
                            'id' => 'master',
                            'type' => 'master',
                            'connection' => [
                                'type' => DbalTools::CONNECTION_TYPE_PDOMYSQL,
                                'options' => [
                                    'dbname' => 'doctrine_tests_tmp',
                                    'user' => 'root',
                                    'password' => '',
                                    'host' => '127.0.0.1',
                                    'charset' => 'utf8'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        $this->toolkit = $toolkit;
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

    public function tearDown()
    {
        $this->toolkit = null;
        \Mockery::close();
    }

    /**
     * @return CollectionManager
     */
    protected function mockColectionManager()
    {
        $collectionMock = \Mockery::mock('umi\orm\collection\SimpleHierarchicCollection');
        $collectionMock->shouldReceive('getMetadata')
            ->andReturn([]);
        $objectSetMock = \Mockery::mock('umi\orm\objectset\ObjectSet');
        $selectorFactoryMock = \Mockery::mock('umi\orm\toolbox\factory\SelectorFactory');
        $collectionMock = new Selector(
            $collectionMock,
            $objectSetMock,
            $selectorFactoryMock
        );
        $managerMock = \Mockery::mock('umi\orm\collection\CollectionManager');
        $managerMock
            ->shouldReceive('getCollection')
            ->andReturn($collectionMock);
        return $managerMock;
    }
}
