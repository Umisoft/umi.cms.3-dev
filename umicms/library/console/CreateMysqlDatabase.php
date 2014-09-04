<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Doctrine\DBAL\Driver\PDOConnection;
use PDO;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use umicms\project\Environment;

/**
 * Создает БД, генерирует файл с конфигурацией БД для всех проектов
 */
class CreateMysqlDatabase extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('create:mysql-database')
            ->setDescription('Create mysql database for UMI.CMS')
            ->addArgument(
                'dbname',
                InputArgument::REQUIRED,
                'Database name.'
            )->addArgument(
                'user',
                InputArgument::REQUIRED,
                'Database user with CREATE TABLE permissions.'
            )->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Database user password.'
            )->addArgument(
                'host',
                InputArgument::OPTIONAL,
                'Database host.',
                'localhost'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbname = $input->getArgument('dbname');
        $host = $input->getArgument('host');
        $user = $input->getArgument('user');
        $password = $input->getArgument('password');

        $connection = new PDO("mysql:host=" . $host, $user, $password);

        $output->writeln('<info>Creating database: "' . $dbname . '"</info>');
        $sql = <<<SQL
CREATE DATABASE IF NOT EXISTS `$dbname`
DEFAULT CHARACTER SET = utf8
DEFAULT COLLATE = utf8_unicode_ci;
SQL;

        if (!$connection->exec($sql)) {
           throw new \PDOException('Cannot create database: ' . var_export($connection->errorInfo(), true));
        }

        $output->writeln('<info>Writing database configuration</info>');

        $config = <<<EOF
<?php

use umi\\dbal\\toolbox\\DbalTools;

return [
    [
        'id'     => 'master',
        'type'   => 'master',
        'connection' => [
            'type' => DbalTools::CONNECTION_TYPE_PDOMYSQL,
            'options' => [
                'dbname' => '{$dbname}',
                'user' => '{$user}',
                'password' => '{$password}',
                'host' => '{$host}',
                'charset' => 'utf8'
            ]
        ]
    ]
];
EOF;

        file_put_contents(Environment::$directoryRoot . '/configuration/db.config.php', $config);
    }
}
 