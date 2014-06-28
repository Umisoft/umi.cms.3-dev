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

/**
 * Настройки окружения UMI.CMS.
 */
class Environment
{
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

}
