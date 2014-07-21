<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project;

use umi\http\Response;

/**
 * Настройки окружения UMI.CMS.
 */
class Environment
{
    /**
     * @var string $currentMode имя окружения
     */
    public static $currentMode = 'development';
    /**
     * @var bool $displayErrors отображать ли ошибки по месту их возникновения
     */
    public static $displayErrors = true;
    /**
     * Уровень вывода ошибок
     */
    public static $errorReporting = E_ALL;
    /**
     * @var int $startTime время запуска приложения
     */
    public static $startTime = 0;
    /**
     * @var bool $showExceptionTrace скрытие/отображение трейса исключений
     */
    public static $showExceptionTrace = true;
    /**
     * @var bool $showExceptionStack скрытие/отображение стека предыдущих исключений
     */
    public static $showExceptionStack = true;
    /**
     * @var string $bootConfig пользовательская конфигурация загрузчика
     */
    public static $bootConfig;
    /**
     * @var string $directoryCoreError директория шаблонов ошибок ядра UMI.CMS
     */
    public static $directoryCoreError;
    /**
     * @var string $directoryProjects публичная директория
     */
    public static $directoryPublic = ".";
    /**
     * @var string $directoryConfiguration публичная директория
     */
    public static $directoryConfiguration = ".";
    /**
     * @var string $directoryAssets директория с ассетами проекта
     */
    public static $directoryAssets = ".";
    /**
     * @var string $baseUrl базовый URL для ресурсов проектов
     */
    public static $baseUrl = "";
    /**
     * @var string $timezone таймзона сервера по умолчанию
     */
    public static $timezone = 'UTC';

    /**
     * Инициализирует окружение настройками из конфигурации
     * @param array $config
     * @throws \RuntimeException
     */
    public static function init($config)
    {
        if (!is_array($config)) {
            throw new \RuntimeException(
                sprintf('Environment configuration should be an array.')
            );
        }

        if (!isset($config['currentMode']) || !isset($config[$config['currentMode']])) {
            throw new \RuntimeException(
                sprintf('Environment configuration is corrupted. Option "%s" required.', 'currentMode')
            );
        }

        self::$currentMode = $config['currentMode'];
        $modeConfig = $config[self::$currentMode];

        foreach ($modeConfig as $name => $value) {
            self::${$name} = $value;
        }

        date_default_timezone_set(self::$timezone);

        error_reporting(self::$errorReporting);
        ini_set('display_errors', (bool) self::$displayErrors);

        register_shutdown_function(function() {
            $error = error_get_last();
            if (is_array($error) && in_array($error['type'], array(E_ERROR))) {
                self::reportCoreError('error.phtml', ['e' => $error]);
            }
        });
    }

    /**
     * Выводит сообщение об ошибке ядра UMI.CMS
     * @param string $templateName
     * @param array $scope
     * @param int $responseStatusCode
     */
    public static function reportCoreError($templateName, array $scope = [], $responseStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $scope['showTrace'] = self::$showExceptionTrace;
        $scope['showStack'] = self::$showExceptionStack;

        $templatePath = (self::$directoryCoreError ?: CMS_DIR . '/error') . '/' . $templateName;
        if (file_exists($templatePath)) {
            extract($scope);

            ob_start();
            /** @noinspection PhpIncludeInspection */
            require $templatePath;
            $content = ob_get_clean();
        } else {
            $content = 'An error has occurred.';
        }

        $response = new Response();
        $response->setContent($content);
        $response->setStatusCode($responseStatusCode);
        $response->send();
    }

}
