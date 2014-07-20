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
use SplFileInfo;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use umicms\Utils;

/**
 * Упаковывает файлы окружения в пакет.
 */
class PackEnvironmentCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pack:environment')
            ->setDescription('Pack UMI.CMS environment files')
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'Output directory for package.',
                '.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list ($version) = Utils::getCurrentGitVersion();

        $outputPharPath = $input->getArgument('output') . '/' . 'environment.phar';

        if (is_file($outputPharPath)) {
            unlink($outputPharPath);
        }

        $phar = new Phar($outputPharPath, 0, 'environment.phar');
        $phar->setMetadata('UMI.CMS Environment Version: ' . $version);
        $phar->setSignatureAlgorithm(Phar::SHA1);

        $phar->startBuffering();

        $output->writeln('<info>Packing environment files...</info>');

        $rootDir = dirname(CMS_DIR);
        $phar->addFile($rootDir . '/.htaccess', '.htaccess.dist');

        $this->packFile($phar, new SplFileInfo($rootDir . '/LICENSE'));

        $this->packFile($phar, new SplFileInfo($rootDir . '/bin/umi'));

        $this->packFile($phar, new SplFileInfo($rootDir . '/public/.htaccess'));
        $this->packFile($phar, new SplFileInfo($rootDir . '/public/favicon.ico'));
        $this->packFile($phar, new SplFileInfo($rootDir . '/public/index.php'));

        $this->packFile($phar, new SplFileInfo($rootDir . '/configuration/core.php'));
        $this->packFile($phar, new SplFileInfo($rootDir . '/configuration/environment.config.php'));
        $this->packFile($phar, new SplFileInfo($rootDir . '/configuration/project.config.dist.php'));
        $this->packFile($phar, new SplFileInfo($rootDir . '/configuration/tools.settings.config.dist.php'));
        $this->packFile($phar, new SplFileInfo($rootDir . '/configuration/db.config.dist.php'));


        $output->writeln('<info>Done.</info>');
        $output->writeln('<info>Packing frontend files...</info>');

        $finder = new Finder();
        $finder->files()
            ->exclude('samples')
            ->in($rootDir . '/public/umi-admin/production');

        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $this->packFile($phar, $file, $progress);
            $progress->advance();
        }

        $progress->setMessage('Done.');
        $progress->finish();

        $output->writeln('');
        $output->writeln('<info>Writing package on disc...</info>');
        $this->setStub($phar);
        $phar->stopBuffering();

        unset($phar);

        $output->writeln('<info>Complete.</info>');
    }



    /**
     * Устанавливает заглушку
     * @param Phar $phar
     */
    private function setStub(Phar $phar)
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

Phar::mapPhar('environment.phar');

__HALT_COMPILER();
EOF;

        $phar->setStub($stub);
    }

    /**
     * Добавляет файл в пакет.
     * @param Phar $phar
     * @param SplFileInfo $file
     * @param ProgressBar $progress
     */
    private function packFile(Phar $phar, SplFileInfo $file, ProgressBar $progress = null)
    {
        $localPath = strtr(str_replace(dirname(CMS_DIR) . '/', '', $file->getRealPath()), '\\', '/');

        if ($progress) {
            $progress->setMessage('Packing "' . $localPath . '"');
        }

        $phar->addFile($file, $localPath);
    }
}
