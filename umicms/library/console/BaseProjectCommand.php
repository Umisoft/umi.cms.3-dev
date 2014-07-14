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
        $this
            ->addArgument(
                'uri',
                InputArgument::REQUIRED,
                'URI проекта'
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

        $bootstrap = new Bootstrap();
        $toolkit = $bootstrap->getToolkit();

        $output->writeln('<info>Диспетчеризация до проекта "' . $projectUri . '"</info>');

        /**
         * @var Request $request
         */
        $request = Request::create($projectUri);

        $toolkit->overrideService('umi\http\Request', function() use ($request) {
            return $request;
        });

        $bootstrap->routeProject();
        $output->writeln('<info>Директория проекта "' . $bootstrap->getProjectDirectory() . '"</info>');

        return $bootstrap;
    }
}
 