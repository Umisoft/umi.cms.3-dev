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
use Phar;
use umicms\install\exception\RuntimeException;

/**
 * Распаковывает проект.
 */
class ExtractProject implements ICommandInstall
{
    /**
     * @var Installer $installer инсталятор
     */
    private $installer;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->installer = $installer;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $config = $this->installer->getConfig();

        if (!isset($config['projectName']) || !in_array($config['projectName'], $this->installer->getTypeProject())) {
            throw new RuntimeException('Неудалось скачать проект.');
        }

        // TODO: Распаковывается файл проекта, в зависимости от выбранного типа

        $composer = new Phar(INSTALL_ROOT_DIR . '/demo-' . $config['projectName'] . '.phar');
        if (!$composer->extractTo(INSTALL_ROOT_DIR . '/public/default', null, true)) {
            throw new RuntimeException(
                'Неудалось извлечь project.'
            );
        }

        $project = <<<EOF
<?php

return [
    'default' => require(dirname(__DIR__) . '/public/default/configuration/project.config.php')
];
EOF;

        file_put_contents(ENVIRONMENT_CONFIG_DIR . '/projects.config.php', $project);

        if (
            !isset($config['db']['dbname']) ||
            !isset($config['db']['host']) ||
            !isset($config['db']['login']) ||
            !isset($config['db']['password'])
        ) {
            throw new RuntimeException(
                'Отсутствуют данные для соединение с БД.'
            );
        }

        if (!isset($config['projectName']) || !in_array($config['projectName'], $this->installer->getTypeProject())) {
            throw new RuntimeException('Неизвестный тип проекта.');
        }

        $this->installer->createConfig(
            ENVIRONMENT_CONFIG_DIR . '/db.config.dist.php',
            ENVIRONMENT_CONFIG_DIR . '/db.config.php',
            ['%dbname%', '%user%', '%password%', '%host%'],
            [$config['db']['dbname'], $config['db']['login'], $config['db']['password'], $config['db']['host']]
        );

        copy(
            ENVIRONMENT_CONFIG_DIR . '/db.config.php',
            PROJECT_CONFIG_DIR . '/db.config.php'
        );
        copy(
            ENVIRONMENT_CONFIG_DIR . '/project.config.dist.php',
            PROJECT_CONFIG_DIR . '/project.config.php'
        );

        $this->installer->createConfig(
            ENVIRONMENT_CONFIG_DIR . '/tools.settings.config.dist.php',
            PROJECT_CONFIG_DIR . '/tools.settings.config.php',
            ['%tableNamePrefix%'],
            ['default_']
        );

        return true;
    }
}
 