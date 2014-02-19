<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms;

/**
 * Настройки окружения UMI.CMS.
 */
class Environment
{
    /**
     * @var array $bootConfigMaster коробочная конфигурация загрузчика
     */
    public $bootConfigMaster;
    /**
     * @var string $bootConfigLocal пользовательская конфигурация загрузчика
     */
    public $bootConfigLocal;
    /**
     * @var string $directoryCms директория ядра UMI.CMS
     */
    public $directoryCms;
    /**
     * @var string $directoryCmsProject директория файлов проекта UMI.CMS
     */
    public $directoryCmsProject;
    /**
     * @var string $directoryProjects директория для пользовательских проектов
     */
    public $directoryProjects;
    /**
     * @var string $projectConfiguration файл с настройками проектов
     */
    public $projectConfiguration = '%directory-project%/configuration/projects.config.php';

}
