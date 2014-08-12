<?php
/**
 * Подключает ядро UMI.CMS с конфигурацией необходимого окружения
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\Environment;

error_reporting(-1);
ini_set('display_errors', 1);

$environments = is_file(__DIR__ . '/environment.config.php') ? require (__DIR__ . '/environment.config.php') : [];

global $environmentMode;

$corePath = dirname(__DIR__) . '/umicms/bootstrap.php';

if (!is_string($environmentMode) && isset($environments['defaultMode'])) {
    $environmentMode = $environments['defaultMode'];

}

if (!isset($environments[$environmentMode])) {
    throw new \RuntimeException(
        sprintf('Environment configuration is corrupted. Configuration for mode "%s" undefined.', $environmentMode)
    );
}

if (isset($environments[$environmentMode]['corePath'])) {
    $corePath = $environments[$environmentMode]['corePath'];
}

/** @noinspection PhpIncludeInspection */
require $corePath;

Environment::init($environments[$environmentMode]);
