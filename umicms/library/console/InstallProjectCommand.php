<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Устанавливает проект, обновляет схемы проекта и его данные.
 */
class InstallProjectCommand extends BaseProjectCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('install:project')
            ->setDescription('Install project: sync database scheme, load project dump');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $syncCommand = $this->getApplication()->find('project:sync-scheme');
        $syncCommand->run($input, $output);

        $loadDumpCommand = $this->getApplication()->find('project:load-dump');
        $loadDumpCommand->run($input, $output);
    }

}
