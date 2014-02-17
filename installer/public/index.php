<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umicms\Bootstrap;
use umicms\Environment;

$vendorDirectory = dirname(dirname(__DIR__)) . '/vendor';
$autoLoaderPath = $vendorDirectory . '/autoload.php';

ini_set('error_reporting', -1);
ini_set('display_errors', 1);

$startTime = microtime(true);

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
require $autoLoaderPath;

$env = new Environment;

$directoryCms = dirname(dirname(__DIR__)) . '/umicms';
$directoryCore = $directoryCms . '/library';
$directoryProject = dirname(dirname(__DIR__));

$toolkitPath = $directoryProject . '/vendor/umi/framework-dev/library';

defined('FRAMEWORK_TOOLKIT_DIR') or define('FRAMEWORK_TOOLKIT_DIR', $toolkitPath);

$env->bootConfigMaster = $directoryCms . '/configuration/boot.config.php';
$env->bootConfigLocal = $directoryProject . '/configuration/boot.config.php';
$env->projectConfiguration =  $directoryProject . '/configuration/projects.config.php';
$env->directoryCore = $directoryCore;
$env->directoryProject = $directoryProject;



$bootstrap = new Bootstrap($env);
$bootstrap->run();