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
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Упаковывает файлы проекта в пакет.
 */
class PackProjectCommand extends BaseProjectCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('pack:project')
            ->setDescription('Pack project files into package.')
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'Output directory for package.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bootstrap = $this->dispatchToProject($input, $output);

        $projectName = $bootstrap->getProjectName();

        $outputPharPath = $input->getArgument('output');
        if (!$outputPharPath) {
            $outputPharPath = './' .  $projectName . '.phar';
        }

        if (is_file($outputPharPath)) {
            unlink($outputPharPath);
        }

        $phar = new Phar($outputPharPath, 0, $projectName . '.phar');
        $phar->setSignatureAlgorithm(Phar::SHA1);

        $phar->startBuffering();

        $style = new OutputFormatterStyle('blue', null, array('bold'));
        $output->getFormatter()->setStyle('process', $style);

        $output->writeln('<process>Packing project files...</process>');
        $this->addProjectFiles($bootstrap->getProjectDirectory(), $phar, $output);

        $output->writeln('<process>Writing package...</process>');
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
     * @param string $projectDirectory директория проекта
     * @param Phar $phar
     * @param OutputInterface $output
     */
    private function addProjectFiles($projectDirectory, Phar $phar, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()
            ->notName('db.config.php')
            ->notName('project.config.php')
            ->notName('tools.settings.config.php')
            ->in($projectDirectory);

        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $progress->advance();
            $progress->setMessage('Pack file ' . $this->packFile($projectDirectory, $phar, $file));
        }
        $progress->setMessage('Pack complete');
        $progress->finish();
    }

    /**
     * Добавляет файл проекта в пакет.
     * @param string $projectDirectory директория проекта
     * @param Phar $phar
     * @param SplFileInfo $file
     * @return string
     */
    private function packFile($projectDirectory, Phar $phar, SplFileInfo $file)
    {
        $localPath = strtr(str_replace($projectDirectory . '/', '', $file->getRealPath()), '\\', '/');

        $phar->addFile($file, $localPath);

        return $localPath;
    }

}
