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

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use umi\http\Request;
use umicms\project\Bootstrap;

/**
 * Абстрактный класс консольной команды.
 */
class BaseProjectCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addArgument(
            'uri',
            InputArgument::REQUIRED,
            'Project URI (Ex: http://localhost)'
        );
    }

    /**
     * Производит диспетчеризацию до проекта.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return Bootstrap
     */
    protected function dispatchToProject(InputInterface $input, OutputInterface $output)
    {
        $projectUri = trim($input->getArgument('uri'));

        $style = new OutputFormatterStyle('blue', null, array('bold'));
        $output->getFormatter()->setStyle('process', $style);


        /**
         * @var Request $request
         */
        $request = Request::create($projectUri);

        $bootstrap = new Bootstrap($request);

        $output->writeln('<info>Dispatching to "' . $projectUri . '"</info>');

        $bootstrap->dispatchProject();

        $output->writeln('<info>Project name: "' . $bootstrap->getProjectName() . '"</info>');
        $output->writeln('<info>Project directory: "' . $bootstrap->getProjectDirectory() . '"</info>');

        return $bootstrap;
    }
}
 