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
use umicms\project\Environment;

error_reporting(-1);
ini_set('display_errors', 1);

mb_internal_encoding("utf8");

/**
 * Директория с файлами ядра UMI.CMS
 */
defined('VENDOR_DIR') or define('VENDOR_DIR', dirname(__DIR__) . '/vendor');

/**
 * @var ClassLoader
 */
$classLoader = require VENDOR_DIR . '/autoload.php';

Environment::$classLoader = $classLoader;
Environment::$startTime = microtime(true);

$versionInfo = require __DIR__ . '/version.php';
if (!is_array($versionInfo) || count($versionInfo) !== 2) {
    throw new \RuntimeException('Version file corrupted.');
}

/**
 * Версия UMI.CMS
 */
define('CMS_VERSION', $versionInfo[0]);
/**
 * Дата выхода версии UMI.CMS
 */
define('CMS_VERSION_DATE', $versionInfo[1]);
/**
 * Директория с файлами ядра UMI.CMS
 */
define('CMS_DIR', __DIR__);
/**
 * Директория с коробочными файлами проекта CMS.
 */
define('CMS_PROJECT_DIR', __DIR__ . '/project');
/**
 * Директория с инструментами CMS.
 */
define('CMS_LIBRARY_DIR', __DIR__ . '/library');
/**
 * Директория с инструментами фреймворка.
 */
define('FRAMEWORK_LIBRARY_DIR', VENDOR_DIR . '/umisoft/umi.framework');
