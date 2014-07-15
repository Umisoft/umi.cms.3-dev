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
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;

/**
 * Упаковывает ядро в пакет.
 */
class PackCoreCommand extends BaseCommand
{
    /**
     * @var string $version
     */
    public $version;
    /**
     * @var string $versionDate
     */
    public $versionDate;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pack-core')
            ->setDescription('Упаковывает ядро в пакет')
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'Директория, в которой будет создан пакет.',
                '.'
            )
            ->addArgument(
                'obfuscate',
                InputArgument::OPTIONAL,
                'Если установлено, ядро будет обфусцировано.',
                false
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectVersionInfo();
        $outputPharPath = $input->getArgument('output') . '/' . 'umicms.phar';

        if (is_file($outputPharPath)) {
            unlink($outputPharPath);
        }

        $phar = new Phar($outputPharPath, 0, 'umicms.phar');
        $phar->setSignatureAlgorithm(Phar::SHA1);

        $phar->startBuffering();

        $style = new OutputFormatterStyle('blue', null, array('bold'));
        $output->getFormatter()->setStyle('process', $style);

        $output->writeln('<process>Упаковка файлов ядра</process>');
        $this->addCoreFiles($phar, $input, $output);
        $output->writeln('');

        $output->writeln('<process>Упаковка вендоров</process>');
        $this->addVendorFiles($phar, $input, $output);
        $output->writeln('');

        $output->writeln('<process>Запись пакета в файл</process>');
        $this->setStub($phar);
        $phar->stopBuffering();

        unset($phar);

        $output->writeln('<process>Complete.</process>');
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

Phar::mapPhar('umicms.phar');

require 'phar://umicms.phar/umicms/bootstrap.php';

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
    private function addCoreFiles(Phar $phar, InputInterface $input, OutputInterface $output)
    {
        $obfuscate = (bool) $input->getArgument('obfuscate');

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->notName('CoreCompiler.php')
            ->in(CMS_DIR);

        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $this->packFile($phar, $file, $obfuscate, $progress);
            $progress->advance();
        }
        $progress->setMessage('Complete.');

        $progress->finish();
    }

    /**
     * Добавляет вендоров в phar
     * @param Phar $phar
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function addVendorFiles(Phar $phar, InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()
            ->name('*.php')
            ->exclude('Tests')
            ->notName('CoreCompiler.php')
            ->in(VENDOR_DIR);


        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $this->packFile($phar, $file, false, $progress);
            $progress->advance();
        }
        $progress->setMessage('Complete.');

        $progress->finish();
    }

    /**
     * Добавляет файл в пакет.
     * @param Phar $phar
     * @param SplFileInfo $file
     * @param bool $obfuscate обфусцировать ли файл
     * @param ProgressBar $progress
     */
    private function packFile(Phar $phar, SplFileInfo $file, $obfuscate, ProgressBar $progress)
    {
        $localPath = strtr(str_replace(dirname(VENDOR_DIR) . '/', '', $file->getRealPath()), '\\', '/');

        $progress->setMessage('Запаковываю "' . $localPath . '"');
        if ($obfuscate && $file->getExtension() == 'php') {
            $contents = file_get_contents($file);
            $contents = Obfuscator::obfuscate($contents);
            $phar->addFromString($localPath, $contents);
        } elseif ($localPath === 'umicms/Bootstrap.php') {
            $contents = file_get_contents($file);
            $contents = str_replace('%version%', $this->version, $contents);
            $contents = str_replace('%versionDate%', $this->versionDate, $contents);
            $phar->addFromString($localPath, $contents);
        } else {
            $phar->addFile($file, $localPath);
        }
    }

    /**
     * Определяет версию (хэш последнего коммита, либо тэг версии) и дату версии
     * @throws \RuntimeException
     */
    private function detectVersionInfo()
    {
        $process = new Process('git log --pretty="%H" -n1 HEAD');
        if ($process->run() != 0) {
            throw new \RuntimeException('Cannot detect last version hash. Can\'t run git log..');
        }

        $this->version = trim($process->getOutput());

        $process = new Process('git describe --tags HEAD');
        if ($process->run() == 0) {
            $this->version = trim($process->getOutput());
        }

        $process = new Process('git log -n1 --pretty=%ci HEAD');
        if ($process->run() != 0) {
            throw new \RuntimeException('Cannot detect last version date. Can\'t run git log.');
        }

        $date = new \DateTime(trim($process->getOutput()));
        $date->setTimezone(new \DateTimeZone('UTC'));

        $this->versionDate = $date->format('Y-m-d H:i:s');
    }
}

/**
 * Obfuscator
 */
class Obfuscator
{
    /**
     * @var array $ignoredVariables skiped vars
     */
    public static $ignoredVariables = ['$this'];

    /**
     * Обфусцирует контент
     * @param $content
     * @return string
     */
    public static function obfuscate($content)
    {
        $tokens = token_get_all($content);

        $tokens = self::cleanup($tokens);
        $tokens = self::replaceLocalVariables($tokens);

        $result = "";
        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];
            $tokenData = is_array($token) ? $token[1] : $token;

            $result .= $tokenData;
        }

        return $result;
    }

    /**
     * Cleanup tokens.
     * @param array $tokens
     * @return array
     */
    private static function cleanup(array $tokens)
    {
        $newTokens = array();
        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];
            if (is_string($token)) {
                $newTokens[] = $token;
                continue;
            }

            $tokenType = $token[0];
            $tokenData = $token[1];
            $tokenLine = $token[2];

            $prevToken = count($newTokens) ? $newTokens[count($newTokens) - 1] : null;
            $prevTokenType = is_array($prevToken) ? $prevToken[0] : null;
            $prevTokenData = is_array($prevToken) ? $prevToken[1] : $prevToken;

            if (in_array($tokenType, array(T_COMMENT, T_DOC_COMMENT))) {
                continue;
            }

            if ($tokenType == T_WHITESPACE) {
                if ($prevTokenType == T_WHITESPACE || in_array($prevTokenData, array('{', '}', ';'))) {
                    continue;
                }
                $tokenData = str_replace("\t", " ", $tokenData);
                $tokenData = str_replace("\n", "", $tokenData);
                $tokenData = str_replace("\r", "", $tokenData);
            }

            // fix heredoc
            if ($tokenType == T_END_HEREDOC) {
                $tokens[$i + 1][1] = $tokens[$i + 1][1] . "\r";
            }
            $newTokens[] = array(
                $tokenType,
                $tokenData,
                $tokenLine,
                token_name($tokenType)
            );

        }

        return $newTokens;
    }

    /**
     * Obfuscate tokens.
     * @param array $tokens
     * @return array
     */
    private static function replaceLocalVariables($tokens)
    {
        $newTokens = array();

        $inFunction = false;
        $inClass = false;
        $inClassDeclaration = false;
        $funcInstructionCounter = 0;
        $classInstructionCounter = 0;
        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];
            if (is_string($token)) {
                // calc instructions {} count
                if ($inFunction) {
                    if ($token == "{") {
                        $funcInstructionCounter++;
                    }
                    if ($token == "}") {
                        $funcInstructionCounter--;
                        if ($funcInstructionCounter == 0) {
                            $inFunction = false;
                            $inClassDeclaration = true;
                        }
                    }
                }
                if ($inClass) {
                    if ($token == "{") {
                        $classInstructionCounter++;
                    }
                    if ($token == "}") {
                        $classInstructionCounter--;
                        if ($classInstructionCounter == 0) {
                            $inClass = false;
                            $inClassDeclaration = false;
                        }
                    }
                }

                $newTokens[] = $token;
                continue;
            }

            $tokenType = $token[0];
            $tokenData = $token[1];

            // T_CURLY_OPEN fix
            if ($tokenType == T_CURLY_OPEN) {
                if ($inFunction) {
                    $funcInstructionCounter++;
                }
                if ($inClass) {
                    $classInstructionCounter++;
                }
            }

            $prevTokenType = isset($tokens[$i - 1]) && is_array($tokens[$i - 1]) ? $tokens[$i - 1][0] : null;

            if ($tokenType == T_FUNCTION) {
                $funcInstructionCounter = 0;
                $inFunction = true;
                $inClassDeclaration = false;
            }

            if ($tokenType == T_CLASS) {
                $classInstructionCounter = 0;
                $inClass = true;
                $inClassDeclaration = true;
            }

            if (($tokenType == T_VARIABLE && !$inClass) || ($tokenType == T_VARIABLE && !$inClassDeclaration && ($prevTokenType !== T_DOUBLE_COLON))) {
                if ((substr($tokenData, 0, 2) != '$_') && !in_array($tokenData, self::$ignoredVariables)) {
                    $tokenData = str_replace("$", "", $tokenData);
                    $token[1] = '$v' . md5($tokenData);
                }
            }

            $newTokens[] = $token;
        }

        return $newTokens;
    }

}

