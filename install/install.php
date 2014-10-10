<?php

use GuzzleHttp\Stream;
use umicms\install\command\CheckDb;
use umicms\install\command\CheckLicense;
use umicms\install\command\CheckProjectType;
use umicms\install\command\CreateSupervisor;
use umicms\install\command\DbConfig;
use umicms\install\command\DownloadCore;
use umicms\install\command\DownloadEnvironment;
use umicms\install\command\DownloadProject;
use umicms\install\command\ExtractCore;
use umicms\install\command\ExtractEnvironment;
use umicms\install\command\ExtractProject;
use umicms\install\command\InstallCommandRegistry;
use umicms\install\command\InstallProject;
use umicms\install\command\LoadDump;
use umicms\install\command\SaveDbConfig;
use umicms\install\command\SiteAccess;
use umicms\install\command\StepsInfo;
use umicms\install\command\TrialLicense;
use umicms\install\exception\RuntimeException;
use umicms\install\installer\Installer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use umicms\install\installer\Rule;

define('INSTALL_ROOT_DIR', getcwd());
define('CMS_CORE_PHAR', INSTALL_ROOT_DIR . '/umicms.phar');
define('CMS_CORE_PHP', INSTALL_ROOT_DIR . '/configuration/core.php');
define('PROJECT_CONFIG_DIR', INSTALL_ROOT_DIR . '/public/default/configuration');
define('ENVIRONMENT_CONFIG_DIR', INSTALL_ROOT_DIR . '/configuration');
define('ENVIRONMENT_PHAR', INSTALL_ROOT_DIR . '/environment.phar');

$vendorDirectory = dirname(__DIR__) . '/vendor';
$autoLoaderPath = $vendorDirectory . '/autoload.php';

error_reporting(-1);
ini_set('display_errors', 1);
set_time_limit(0);
ini_set('max_execution_time', 0);

/** @noinspection PhpIncludeInspection */
require $autoLoaderPath;

$request = new Request($_GET);
$response = new Response();

try {
    $rules = new Rule(['pdo', 'mbstring', 'curl']);
    $rules->check();
} catch (\Exception $e) {
    $response->setContent(json_encode([
        'message' => $e->getMessage(),
    ]));
    $response->headers->set('Content-Type', 'application/json');
    $response->setStatusCode(400);
    $response->send();
    exit;
}

mb_internal_encoding("utf8");

session_start();

if (!isset($_SESSION['configFileName'])) {
    $_SESSION['configFileName'] = './config-' . time();
}

$installer = new Installer($_SESSION['configFileName']);

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
$registry->add(new ExtractCore($installer), 'coreExtract');
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
} catch (\Exception $e) {
    $response->setContent(json_encode([
        'message' => $e->getMessage(),
        'overlay' => $e instanceof RuntimeException ? $e->getOverlay() : null
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