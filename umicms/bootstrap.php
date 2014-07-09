<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\Environment;

error_reporting(-1);
ini_set('display_errors', 1);

mb_internal_encoding("utf8");

$vendorDirectory = dirname(__DIR__) . '/vendor';
require $vendorDirectory . '/autoload.php';


Environment::$startTime = microtime(true);


$toolkitPath = $vendorDirectory . '/umisoft/umi.framework';

/**
 * Директория с ядра UMI.CMS
 */
defined('CMS_DIR') or define('CMS_DIR', __DIR__);
/**
 * Директория с коробочными файлами проекта CMS.
 */
defined('CMS_PROJECT_DIR') or define('CMS_PROJECT_DIR', __DIR__ . '/project');
/**
 * Директория с инструментами CMS.
 */
defined('CMS_LIBRARY_DIR') or define('CMS_LIBRARY_DIR', __DIR__ . '/library');
/**
 * Директория с инструментами фреймворка.
 */
defined('FRAMEWORK_LIBRARY_DIR') or define('FRAMEWORK_LIBRARY_DIR', $toolkitPath);
