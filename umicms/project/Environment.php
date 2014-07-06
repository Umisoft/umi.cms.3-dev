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
     * Режим разработки
     */
    const DEV_MODE = 'devMode';
    /**
     * Режим "продакшн"
     */
    const PRODUCTION_MODE = 'productionMode';
    /**
     * Текущий режим
     */
    const CURRENT_MODE = 'currentMode';
    /**
     * Уровень вывода ошибок
     */
    const ERROR_REPORTING = 'errorReporting';
    /**
     * Скрытие/отображение ошибок на экране
     */
    const DISPLAY_ERRORS = 'displayErrors';
    /**
     * Скрытие/отображение трейса исключений
     */
    const DISPLAY_EXCEPTION_TRACE = 'displayExceptionTrace';
    /**
     * Скрытие/отображение стека предыдущих исключений
     */
    const DISPLAY_EXCEPTION_STACK = 'displayExceptionStack';

    /**
     * @var bool $showExceptionTrace скрытие/отображение трейса исключений
     */
    public static $showExceptionTrace = false;
    /**
     * @var bool $showExceptionStack скрытие/отображение стека предыдущих исключений
     */
    public static $showExceptionStack = false;
    /**
     * @var string $currentMode
     */
    public static $currentMode = self::PRODUCTION_MODE;
    /**
     * @var array $bootConfigMaster коробочная конфигурация загрузчика
     */
    public static $bootConfigMaster;
    /**
     * @var string $bootConfigLocal пользовательская конфигурация загрузчика
     */
    public static $bootConfigLocal;
    /**
     * @var string $directoryCms директория ядра UMI.CMS
     */
    public static $directoryCms;
    /**
     * @var string $directoryCmsError директория шаблонов ошибок ядра UMI.CMS
     */
    public static $directoryCmsError;
    /**
     * @var string $directoryCmsProject директория файлов проекта UMI.CMS
     */
    public static $directoryCmsProject;
    /**
     * @var string $directoryProjects директория для пользовательских проектов
     */
    public static $directoryProjects;
    /**
     * @var string $projectConfiguration файл с настройками проектов
     */
    public static $projectsConfiguration;
    /**
     * @var string $environmentConfiguration файл с настройками окружения
     */
    public static $environmentConfiguration;

    /**
     * Настраивает вывод ошибок.
     * @param array $config
     * @throws \RuntimeException в случае неверной конфигурации
     */
    public static function initErrorReporting($config)
    {
        if (!is_array($config)) {
            throw new \RuntimeException(
                sprintf('Environment configuration should be an array.')
            );
        }
        if (!isset($config[self::CURRENT_MODE]) || !isset($config[$config[self::CURRENT_MODE]])) {
            throw new \RuntimeException(
                sprintf('Environment configuration is corrupted.')
            );
        }
        self::$currentMode = $config[self::CURRENT_MODE];
        $modeConfig = $config[self::$currentMode];
        $errorReporting = isset($modeConfig[self::ERROR_REPORTING]) ? $modeConfig[self::ERROR_REPORTING] : 0;
        $displayErrors = isset($modeConfig[self::DISPLAY_ERRORS]) ? $modeConfig[self::DISPLAY_ERRORS] : 0;
        self::$showExceptionStack = isset($modeConfig[self::DISPLAY_EXCEPTION_STACK]) ? $modeConfig[self::DISPLAY_EXCEPTION_STACK] : false;
        self::$showExceptionTrace = isset($modeConfig[self::DISPLAY_EXCEPTION_TRACE]) ? $modeConfig[self::DISPLAY_EXCEPTION_TRACE] : false;

        error_reporting($errorReporting);
        ini_set('display_errors', $displayErrors);

        register_shutdown_function(function() {
            $error = error_get_last();
            if (is_array($error) && in_array($error['type'], array(E_ERROR))) {
                Environment::reportError('error.phtml', ['e' => $error]);
            }
        });
    }

    /**
     * Выводит сообщение об ошибке
     * @param $templateName
     * @param array $scope
     * @param int $responseStatusCode
     */
    public static function reportError($templateName, array $scope = [], $responseStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $scope['showTrace'] = self::$showExceptionTrace;
        $scope['showStack'] = self::$showExceptionStack;

        $templatePath = self::$directoryCmsError . '/' . $templateName;
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
