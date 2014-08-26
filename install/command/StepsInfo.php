<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\install\command;

use umicms\install\installer\Installer;

/**
 * Заголовки шагов установки.
 */
class StepsInfo implements ICommandInstall
{
    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return [
            'checkLicense' => [
                'title' => 'Проверка лицензии',
                'nextStep' => 'projectType',
                'stepNum' => 1
            ],
            'trial' => [
                'title' => 'Получение триального ключа',
                'nextStep' => 'checkLicense',
                'prevStep' => 'checkLicense'
            ],
            'projectType' => [
                'title' => 'Выбор проекта',
                'nextStep' => 'siteAccess',
                'prevStep' => 'checkLicense',
                'stepNum' => 2
            ],
            'siteAccess' => [
                'title' => 'Настройка доступа к сайту',
                'nextStep' => 'db',
                'prevStep' => 'projectType',
                'stepNum' => 3
            ],
            'db' => [
                'title' => 'Настройки базы данных',
                'nextStep' => 'checkDb',
                'prevStep' => 'siteAccess',
                'stepNum' => 4
            ],
            'checkDb' => [
                'title' => 'Проверка базы данных',
                'nextStep' => 'core'
            ],
            'core' => [
                'title' => 'Скачивание ядра системы',
                'nextStep' => 'coreExtract',
                'stepNum' => 5
            ],
            'coreExtract' => [
                'title' => 'Распаковка ядра системы',
                'nextStep' => 'environment',
                'stepNum' => 5
            ],
            'environment' => [
                'title' => 'Скачивание окружения',
                'nextStep' => 'environmentExtract',
                'stepNum' => 5
            ],
            'environmentExtract' => [
                'title' => 'Распаковка окружения',
                'nextStep' => 'project',
                'stepNum' => 5
            ],
            'project' => [
                'title' => 'Скачивание проекта',
                'nextStep' => 'projectExtract',
                'stepNum' => 6
            ],
            'projectExtract' => [
                'title' => 'Распаковка проекта',
                'nextStep' => 'install',
                'stepNum' => 6
            ],
            'install' => [
                'title' => 'Установка сайта',
                'nextStep' => 'loadDump',
                'stepNum' => 6
            ],
            'loadDump' => [
                'title' => 'Установка данных проекта',
                'nextStep' => 'createSv',
                'stepNum' => 6
            ],
            'createSv' => [
                'title' => 'Создание супервайзера',
                'nextStep' => 'finish',
                'stepNum' => 6
            ],
            'finish' => [
                'title' => 'Установка завершена',
                'stepNum' => 7
            ],
            'allSteps' => 7
        ];
    }
}
 