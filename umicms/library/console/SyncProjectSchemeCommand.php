<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use umicms\module\toolbox\ModuleTools;

/**
 * Синхронизирует и обновляет схему БД для моделей проекта.
 */
class SyncProjectSchemeCommand extends BaseProjectCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('sync:project-scheme')
            ->setDescription('Synchronize database scheme for project.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $bootstrap = $this->dispatchToProject($input, $output);
        $toolkit = $bootstrap->getToolkit();

        /**
         * @var ModuleTools $moduleTools
         */
        $moduleTools = $toolkit->getToolbox(ModuleTools::NAME);

        $progress = $this->startProgressBar($output, count($moduleTools->getModules()));

        foreach ($moduleTools->getModules() as $module) {
            $progress->setMessage('Sync table schemes for module "' . $module->getName() . '"');
            $progress->advance();
            $module->getModelCollection()->syncAllSchemes();
        }

        $progress->setMessage('Complete.');
        $progress->finish();

        $output->writeln('<info>Complete.</info>');
    }

}
