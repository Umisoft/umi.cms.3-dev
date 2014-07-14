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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Базовый класс консольной команды UMI.CMS.
 */
abstract class BaseCommand extends Command
{
    /**
     * Запускает progress bar
     * @param OutputInterface $output
     * @param int $max максимальное количество операций
     * @return ProgressBar
     */
    protected function startProgressBar(OutputInterface $output, $max)
    {
        $output->writeln("");

        $progress = new ProgressBar($output, $max);
        $progress->setFormat("%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%\n<info>%message%</info>" . str_repeat(' ', 40));
        $progress->start();

        return $progress;
    }
}
 