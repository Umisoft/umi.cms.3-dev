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

/**
 * Директория с файлами ядра UMI.CMS
 */
defined('CMS_VENDOR_DIR') or define('VENDOR_DIR', dirname(__DIR__) . '/vendor');

require VENDOR_DIR . '/autoload.php';


Environment::$startTime = microtime(true);

/**
 * Директория с файлами ядра UMI.CMS
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
defined('FRAMEWORK_LIBRARY_DIR') or define('FRAMEWORK_LIBRARY_DIR', VENDOR_DIR . '/umisoft/umi.framework');
