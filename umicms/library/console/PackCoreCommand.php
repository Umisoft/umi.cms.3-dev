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
use umicms\Utils;

/**
 * Упаковывает ядро в пакет.
 */
class PackCoreCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('core:pack')
            ->setDescription('Pack core')
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'Output directory for package.',
                '.'
            )
            ->addArgument(
                'obfuscate',
                InputArgument::OPTIONAL,
                'Obfuscate core.',
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

        $outputPharPath = $input->getArgument('output') . '/' . 'umicms.phar';

        if (is_file($outputPharPath)) {
            unlink($outputPharPath);
        }

        $phar = new Phar($outputPharPath, 0, 'umicms.phar');
        $phar->setMetadata('UMI.CMS Core Version: ' . $version);
        $phar->setSignatureAlgorithm(Phar::SHA1);

        $phar->startBuffering();


        $style = new OutputFormatterStyle('blue', null, array('bold'));
        $output->getFormatter()->setStyle('process', $style);

        $output->writeln('<process>Packing core files...</process>');
        $this->addCoreFiles($phar, $input, $output);
        $output->writeln('');

        $output->writeln('<process>Packing vendor files...</process>');
        $this->addVendorFiles($phar, $output);
        $output->writeln('');

        $output->writeln('<process>Writing package on disc...</process>');
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
            ->notName('version.php')
            ->in(CMS_DIR)
            ->exclude('project/admin/asset');

        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $this->packFile($phar, $file, $obfuscate, $progress);
            $progress->advance();
        }

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->in(CMS_ADMIN_FRONTEND . '/deploy');

        $progress = $this->startProgressBar($output, $finder->count());

        foreach ($finder as $file) {
            $this->packFile($phar, $file, $obfuscate, $progress);
            $progress->advance();
        }
        $progress->setMessage('Complete.');

        $this->addVersionFile($phar);


        $progress->finish();
    }


    /**
     * Добавляет  с версией сборки
     * @param Phar $phar
     */
    private function addVersionFile(Phar $phar) {
        list ($version, $versionDate) = Utils::getCurrentGitVersion();
        $version = <<<EOF
<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return ['{$version}', '{$versionDate}'];
EOF;

        $phar->addFromString('umicms/version.php', $version);
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
        $localPath = strtr(str_replace(dirname(CMS_DIR) . '/', '', $file->getRealPath()), '\\', '/');

        $progress->setMessage('Packing "' . $localPath . '"');
        if ($obfuscate && $file->getExtension() == 'php') {
            $contents = file_get_contents($file);
            $contents = Obfuscator::obfuscate($contents);
            $phar->addFromString($localPath, $contents);
        } else {
            $phar->addFile($file, $localPath);
        }
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

