<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Composer\Autoload\ClassLoader;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\project\Bootstrap;
use umicms\project\Environment;

$vendorDirectory = dirname(dirname(__DIR__)) . '/vendor';
$autoLoaderPath = $vendorDirectory . '/autoload.php';

error_reporting(-1);
ini_set('display_errors', 1);

$umicmsStartTime = microtime(true);

mb_internal_encoding("utf8");

if (!file_exists($autoLoaderPath)) {
    throw new RuntimeException(
        sprintf('Cannot load application. File "%s" not found.', $autoLoaderPath)
    );
}

/** @noinspection PhpIncludeInspection */
/** @var $loader ClassLoader */
$loader = require $autoLoaderPath;

$directoryCms = dirname(dirname(__DIR__)) . '/umicms';
$directoryProjects = dirname(dirname(__DIR__));

Environment::$environmentConfiguration =  $directoryProjects . '/configuration/environment.config.php';
if (!file_exists(Environment::$environmentConfiguration)) {
    throw new RuntimeException(
        sprintf('Cannot configure environment. File "%s" not found.', Environment::$environmentConfiguration)
    );
}

/** @noinspection PhpIncludeInspection */
$environmentConfig = require(Environment::$environmentConfiguration);
Environment::initErrorReporting($environmentConfig);

$toolkitPath = $vendorDirectory . '/umisoft/umi.framework';

defined('CMS_LIBRARY_DIR') or define('CMS_LIBRARY_DIR', $directoryCms . '/library');
defined('FRAMEWORK_LIBRARY_DIR') or define('FRAMEWORK_LIBRARY_DIR', $toolkitPath);
defined('PUBLIC_DIR') or define('PUBLIC_DIR', __DIR__);

Environment::$bootConfigMaster = $directoryCms . '/configuration/boot.config.php';
Environment::$bootConfigLocal = $directoryProjects . '/configuration/boot.config.php';

Environment::$projectsConfiguration =  $directoryProjects . '/configuration/projects.config.php';
Environment::$directoryCms = $directoryCms;
Environment::$directoryCmsError = $directoryCms . '/error';
Environment::$directoryCmsProject = $directoryCms . '/project';
Environment::$directoryProjects = $directoryProjects;

try {
    (new Bootstrap())->run();
} catch (\Exception $e) {

    $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    if ($e instanceof HttpException) {
        $code = $e->getCode();
    }
    Environment::reportError('exception.phtml', ['e' => $e], $code);
}


