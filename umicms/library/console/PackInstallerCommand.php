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
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use umicms\Utils;

/**
 * Упаковывает инсталлятор в пакет.
 */
class PackInstallerCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pack:installer')
            ->setDescription('Pack UMI.CMS installer files')
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'Output directory for package.',
                '.'
            )->addArgument(
                'without-vendors',
                InputArgument::OPTIONAL,
                'Pack installer without vendors.',
                false
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list ($version, $versionDate) = Utils::getCurrentGitVersion();

        $output->writeln('Version: ' . $version);
        $output->writeln('Version date: ' . $versionDate);

        $outputPharPath = $input->getArgument('output') . '/' . 'install.phar';

        if (is_file($outputPharPath)) {
            unlink($outputPharPath);
        }

        $phar = new Phar($outputPharPath, 0, 'install.phar');
        $phar->setMetadata('UMI.CMS Installer Version: ' . $version);
        $phar->setSignatureAlgorithm(Phar::SHA1);

        $phar->startBuffering();

        $output->writeln('<info>Packing installer files...</info>');
        $this->addInstallerFiles($phar, $input, $output);
        $output->writeln('');

        if (!$input->getArgument('without-vendors')) {
            $output->writeln('<info>Packing vendor files...</info>');
            $this->addVendorFiles($phar, $output);
            $output->writeln('');
        }

        $output->writeln('<info>Writing package on disc...</info>');
        $this->setStub($phar);
        $phar->stopBuffering();

        unset($phar);

        rename($outputPharPath, $outputPharPath . '.php');

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

header("Content-Type: text/html; charset=utf-8");

if (!is_writable('.')) {
    die('Проверте права на запись в директориию ' . __DIR__);
}

if (!is_readable('.')) {
    die('Проверте права на чтение директориии ' . __DIR__);
}

set_error_handler(function(\$errno, \$errmsg, \$filename, \$linenum) {
    \$date = date('Y-m-d H:i:s');
    \$f = fopen('./errors.txt', 'a');
    if (!empty(\$f)) {
        \$filename = str_replace(\$_SERVER['DOCUMENT_ROOT'],'',\$filename);
        \$err = "[\$date] \$errmsg \$filename : \$linenum\r\n";
        fwrite(\$f, \$err);
        fclose(\$f);
    }
});

register_shutdown_function(function() {
    \$errors = error_get_last();
    if (is_array(\$errors) && in_array(\$errors['type'], [E_ERROR])) {
        \$f = fopen('./errors.txt', 'a');
        if (!empty(\$f)) {
            foreach (\$errors as \$error) {
                fwrite(\$f, \$error['message'] . ' ' . \$error['file'] . ':' . \$error['line'] . "\r\n");
            }
            fclose(\$f);
        }
    }
});

function recurseCopy(\$src, \$dst) {
    \$dir = opendir(\$src);
    @mkdir(\$dst);
    while(false !== ( \$file = readdir(\$dir)) ) {
        if (( \$file != '.' ) && ( \$file != '..' )) {
            if ( is_dir(\$src . '/' . \$file) ) {
                recurseCopy(\$src . '/' . \$file, \$dst . '/' . \$file);
            }
            else {
                copy(\$src . '/' . \$file, \$dst . '/' . \$file);
            }
        }
    }
    closedir(\$dir);
}

try {
    Phar::mapPhar('install.phar');
} catch(\Exception \$e) {
    echo 'Произошла ошибка при запуске инсталлятора. Убедитесь, что файл был загружен в бинарном режиме. Если ошибка не устранена, обратитесь в СЗ.';
}

if (!file_exists('./resources')) {
    recurseCopy('phar://install.phar/install/resources', './resources');
}

if (count(\$_GET)) {
    require 'phar://install.phar/install/install.php';
} else {
    require 'phar://install.phar/install/install.html';
}

__HALT_COMPILER();
EOF;

        $phar->setStub($stub);
    }

    /**
     * Добавляет файлы ядра в phar
     * @param Phar $phar
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function addInstallerFiles(Phar $phar, InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()
            ->notName('composer.json')
            ->in(dirname(CMS_DIR) . '/install');

        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $this->packFile($phar, $file, $progress);
            $progress->advance();
        }

        $progress->setMessage('Complete.');


        $progress->finish();
    }

    /**
     * Добавляет вендоров в phar
     * @param Phar $phar
     * @param OutputInterface $output
     */
    private function addVendorFiles(Phar $phar, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()
            ->name('*.php')
            ->exclude('Tests')
            ->exclude('tests')
            ->exclude('Test')
            ->exclude('test')
            ->exclude('bin')
            ->exclude('test-suite')
            ->exclude('examples')
            ->exclude('docs')
            ->exclude('doc')
            ->exclude('demo')
            ->exclude('notes')
            ->exclude('autoload_files.php')
            ->in(VENDOR_DIR . '/symfony/http-foundation')
            ->in(VENDOR_DIR . '/guzzlehttp')
            ->in(VENDOR_DIR . '/composer');


        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $this->packFile($phar, $file, $progress);
            $progress->advance();
        }
        $progress->setMessage('Complete.');

        $phar->addFile(VENDOR_DIR . '/autoload.php', 'vendor/autoload.php');

        $files = <<<EOF
<?php
\$vendorDir = dirname(dirname(__FILE__));

return [\$vendorDir . '/guzzlehttp/guzzle/src/functions.php'];

EOF;

        $phar->addFromString('vendor/composer/autoload_files.php', $files);

        $progress->finish();
    }

    /**
     * Добавляет файл в пакет.
     * @param Phar $phar
     * @param SplFileInfo $file
     * @param ProgressBar $progress
     */
    private function packFile(Phar $phar, SplFileInfo $file, ProgressBar $progress)
    {
        $localPath = strtr(str_replace(dirname(CMS_DIR) . DIRECTORY_SEPARATOR, '', $file->getRealPath()), '\\', '/');

        $content = file_get_contents($file);
        if ($file->getExtension() === 'php') {
            $content = $this->stripWhitespace($content);
        }


        $progress->setMessage('Packing "' . $localPath . '"');
        $phar->addFromString($localPath, $content);
    }

    /**
     * Removes whitespace from a PHP source string while preserving line numbers.
     *
     * @param string $source A PHP string
     * @return string The PHP string with the whitespace removed
     */
    private function stripWhitespace($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }

        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }

        return $output;
    }
}