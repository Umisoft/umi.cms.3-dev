<?php

namespace install;

use Composer\Autoload\ClassLoader;
use GuzzleHttp\Stream;
use install\command\CheckDb;
use install\command\CheckLicense;
use install\command\CheckProjectType;
use install\command\CreateSupervisor;
use install\command\DbConfig;
use install\command\DownloadCore;
use install\command\DownloadEnvironment;
use install\command\DownloadProject;
use install\command\ExtractEnvironment;
use install\command\ExtractProject;
use install\command\InstallCommandRegistry;
use install\command\InstallProject;
use install\command\LoadDump;
use install\command\SaveDbConfig;
use install\command\SiteAccess;
use install\command\StepsInfo;
use install\command\TrialLicense;
use install\exception\RuntimeException;
use install\installer\Installer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('ROOT_DIR', dirname(__DIR__));
define('CMS_CORE_PHAR', ROOT_DIR . '/umicms.phar');
define('CMS_CORE_PHP', ROOT_DIR . '/configuration/core.php');
define('PROJECT_CONFIG_DIR', ROOT_DIR . '/public/default/configuration');
define('ENVIRONMENT_CONFIG_DIR', ROOT_DIR . '/configuration');
define('ENVIRONMENT_PHAR', ROOT_DIR . '/environment.phar');

$vendorDirectory = dirname(__DIR__) . '/vendor';
$autoLoaderPath = $vendorDirectory . '/autoload.php';

error_reporting(-1);
ini_set('display_errors', 1);

mb_internal_encoding("utf8");

if (!file_exists($autoLoaderPath)) {
    throw new RuntimeException(
        sprintf('Cannot load application. File "%s" not found.', $autoLoaderPath)
    );
}

/** @noinspection PhpIncludeInspection */
/** @var $loader ClassLoader */
$loader = require $autoLoaderPath;

session_start();

if (!isset($_SESSION['configFileName'])) {
    $_SESSION['configFileName'] = './config-' . time();
}

$installer = new Installer($_SESSION['configFileName']);

$request = new Request($_GET);
$response = new Response();

$registry = new InstallCommandRegistry();
$registry->add(new CheckLicense($installer, $request->query->get('licenseKey')), 'checkLicense');
$registry->add(new TrialLicense($installer, $request->query->get('trial')), 'trial');
$registry->add(new CheckProjectType($installer, $request->query->get('projectType')), 'projectType');
$registry->add(new SiteAccess($installer, $request->query->get('siteAccess')), 'siteAccess');
$registry->add(
    new SaveDbConfig(
        $installer,
        [
            'dbname' => $request->query->get('db[dbname]', null, true),
            'host' => $request->query->get('db[host]', null, true),
            'login' => $request->query->get('db[login]', null, true),
            'password' => $request->query->get('db[password]', null, true)
        ]
    ),
    'db'
);
$registry->add(new CheckDb($installer), 'checkDb');
$registry->add(new DownloadCore($installer), 'core');
$registry->add(new DownloadEnvironment($installer), 'environment');
$registry->add(new ExtractEnvironment($installer), 'environmentExtract');
$registry->add(new DownloadProject($installer), 'project');
$registry->add(new ExtractProject($installer), 'projectExtract');
$registry->add(new InstallProject($installer), 'install');
$registry->add(new LoadDump($installer), 'loadDump');
$registry->add(new CreateSupervisor($installer, $request->query->get('changeAuthData')), 'createSv');

$registry->add(new DbConfig($installer, $request->query->get('getDbConfig')), 'getDbConfig');
$registry->add(new StepsInfo($installer), 'getStepsInfo');

try {
    $result = $registry->get($request->query->get('command'))->execute();
} catch (RuntimeException $e) {
    $response->setContent(json_encode([
        'message' => $e->getMessage(),
        'overlay' => $e->getOverlay()
    ]));
    $response->headers->set('Content-Type', 'application/json');
    $response->setStatusCode(400);
    $response->send();
    exit;
}

$response->setContent(json_encode([
    'result' => $result
]));
$response->headers->set('Content-Type', 'application/json');
$response->send();