<?php
/**
 * Подключает ядро UMI.CMS
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

$environment = is_file(__DIR__ . '/environment.config.php') ? require (__DIR__ . '/environment.config.php') : [];

if (!isset($environment['corePath'])) {
    $environment['corePath'] = dirname(__DIR__) . '/umicms/bootstrap.php';
}

/** @noinspection PhpIncludeInspection */
require $environment['corePath'];

Environment::init($environment);
