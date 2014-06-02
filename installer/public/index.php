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
use umicms\project\Bootstrap;
use umicms\project\Environment;

$vendorDirectory = dirname(dirname(__DIR__)) . '/vendor';
$autoLoaderPath = $vendorDirectory . '/autoload.php';

error_reporting(-1);
ini_set('display_errors', 1);

$umicmsStartTime = microtime(true);

// TODO: error_reporting control
register_shutdown_function(function() {
    $error = error_get_last();
    if (is_array($error) && in_array($error['type'], array(E_ERROR))) {
        http_response_code(500);
        echo $error['message'];
    }
});

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

$toolkitPath = $directoryProjects . '/vendor/umisoft/umi.framework-dev/library';

defined('CMS_LIBRARY_DIR') or define('CMS_LIBRARY_DIR', $directoryCms . '/library');
defined('FRAMEWORK_LIBRARY_DIR') or define('FRAMEWORK_LIBRARY_DIR', $toolkitPath);
defined('PUBLIC_DIR') or define('PUBLIC_DIR', __DIR__);

Environment::$bootConfigMaster = $directoryCms . '/configuration/boot.config.php';
Environment::$bootConfigLocal = $directoryProjects . '/configuration/boot.config.php';

Environment::$projectsConfiguration =  $directoryProjects . '/configuration/projects.config.php';
Environment::$directoryCms = $directoryCms;
Environment::$directoryCmsProject = $directoryCms . '/project';
Environment::$directoryProjects = $directoryProjects;

(new Bootstrap())->run();
