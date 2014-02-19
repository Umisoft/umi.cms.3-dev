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
$directoryProjects = dirname(dirname(__DIR__));

$toolkitPath = $directoryProjects . '/vendor/umi/framework-dev/library';

defined('CMS_LIBRARY_DIR') or define('CMS_LIBRARY_DIR', $directoryCms . '/library');
defined('FRAMEWORK_LIBRARY_DIR') or define('FRAMEWORK_LIBRARY_DIR', $toolkitPath);

$env->bootConfigMaster = $directoryCms . '/configuration/boot.config.php';
$env->bootConfigLocal = $directoryProjects . '/configuration/boot.config.php';
$env->projectConfiguration =  $directoryProjects . '/configuration/projects.config.php';
$env->directoryCms = $directoryCms;
$env->directoryCmsProject = $directoryCms . '/project';
$env->directoryProjects = $directoryProjects;



$bootstrap = new Bootstrap($env);
$bootstrap->run();