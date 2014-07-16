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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use umicms\Utils;

/**
 * Собирает административное приложение UMI.CMS
 */
class BuildAdminFrontentCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('core:build-frontend')
            ->setDescription('Create dump for project data.');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list ($version, $versionDate) = Utils::getCurrentGitVersion();
        $output->writeln('Version: ' . $version);
        $output->writeln('Version date: ' . $versionDate);

        $output->writeln('<info>Installing dependencies...</info>');
        $this->executeRealTimeProcess($output, 'bower install', CMS_ADMIN_FRONTEND);
        $output->writeln('<info>Done.</info>');

        $output->writeln('<info>Installing modules...</info>');
        $this->executeRealTimeProcess($output, 'npm install', CMS_ADMIN_FRONTEND);
        $output->writeln('<info>Done.</info>');

        $versionFile = CMS_ADMIN_FRONTEND . '/styles/_version.scss';
        $output->writeln('<info>Writing version file "' . $versionFile . '" </info>');
        file_put_contents($versionFile, '$images-version: \'' . $version . '\';');
        $output->writeln('<info>Done.</info>');

        $output->writeln('<info>Deploying...</info>');
        $this->executeRealTimeProcess($output, 'grunt deploy', CMS_ADMIN_FRONTEND);
        $output->writeln('<info>Done.</info>');
    }
}
 