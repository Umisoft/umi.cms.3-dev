<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Phar;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use umicms\project\Environment;

/**
 * Упаковывает файлы проекта в пакет.
 */
class PackProjectCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pack-project')
            ->setDescription('Упаковывает файлы проекта в пакет.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Имя проекта'
            )
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'Полый путь пакета, который будет создан.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $projectName = $input->getArgument('name');

        $outputPharPath = $input->getArgument('output');
        if (!$outputPharPath) {
            $outputPharPath = './' . $projectName . '.phar';
        }

        if (is_file($outputPharPath)) {
            unlink($outputPharPath);
        }

        $phar = new Phar($outputPharPath, 0, $projectName . '.phar');
        $phar->setSignatureAlgorithm(Phar::SHA1);

        $phar->startBuffering();

        $style = new OutputFormatterStyle('blue', null, array('bold'));
        $output->getFormatter()->setStyle('process', $style);

        $output->writeln('<process>Упаковка файлов проекта</process>');
        $this->addProjectFiles($projectName, $phar, $output);

        $output->writeln('<process>Запись пакета в файл</process>');
        $this->setStub($phar, $projectName . '.phar');
        $phar->stopBuffering();

        unset($phar);

        $output->writeln('<process>Complete.</process>');
    }

    /**
     * Устанавливает заглушку
     * @param Phar $phar
     * @param string $pharAlias
     */
    private function setStub(Phar $phar, $pharAlias)
    {
        $stub = <<<EOF
<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Phar::mapPhar('{$pharAlias}');

__HALT_COMPILER();
EOF;

        $phar->setStub($stub);
    }

    /**
     * Добавляет файлы проекта в phar
     * @param string $projectName имя проекта
     * @param Phar $phar
     * @param OutputInterface $output
     */
    private function addProjectFiles($projectName, Phar $phar, OutputInterface $output)
    {
        $projectDir = Environment::$directoryProjects . '/' . $projectName;

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->in($projectDir);

        $progress = new ProgressBar($output, $finder->count());
        $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
        $progress->start();

        foreach ($finder as $file) {
            $this->packFile($projectDir, $phar, $file);
            $progress->advance();
            $progress->setMessage('Pack file ' . $file);
        }
        $progress->setMessage('Pack complete');
        $progress->finish();
    }

    /**
     * Добавляет файл проекта в пакет.
     * @param string $projectDir директория проекта
     * @param Phar $phar
     * @param SplFileInfo $file
     */
    private function packFile($projectDir, Phar $phar, SplFileInfo $file)
    {
        $localPath = strtr(str_replace($projectDir . '/', '', $file->getRealPath()), '\\', '/');

        $phar->addFile($file, $localPath);
    }

}
