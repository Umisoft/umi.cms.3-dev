<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms;

use Phar;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;

/**
 * Компилятор ядра UMI.CMS 3 в phar-пакет
 */
class CoreCompiler
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
     * @var string $pharAlias алиас для обращения к пакету через стрим.
     */
    public $pharAlias = 'umicms.phar';
    /**
     * @var bool $obfuscate обфусцировать ли ядро
     */
    public $obfuscate = false;

    /**
     * @var string $outputPharPath полый путь пакета, который будет создан
     */
    private $outputPharPath;
    /**
     * @var string $baseDirectory базовая директория UMI.CMS
     */
    private $baseDirectory;
    /**
     * @var string $coreDirectory директория ядра UMI.CMS
     */
    private $coreDirectory;



    /**
     * Конструктор
     * @param string $outputPharPath полый путь пакета, который будет создан
     */
    public function __construct($outputPharPath)
    {
        if (is_file($outputPharPath)) {
            unlink($outputPharPath);
        }
        $this->outputPharPath = $outputPharPath;
        $this->coreDirectory = dirname(dirname(__FILE__));
        $this->baseDirectory = dirname($this->coreDirectory);

        $this->detectVersionInfo();
    }

    public function compile()
    {
        $phar = new Phar($this->outputPharPath, 0, $this->pharAlias);
        $phar->setSignatureAlgorithm(Phar::SHA1);

        $phar->startBuffering();

        $this->addCoreFiles($phar);
        $this->addVendorFiles($phar);
        $this->setStub($phar);

        $phar->stopBuffering();

        unset($phar);
    }

    /**
     * Устанавливает заглушку
     * @param Phar $phar
     */
    private function setStub(Phar $phar)
    {
        $stub = <<<EOF
<?php

EOF;

        $phar->setStub($stub);
    }

    /**
     * Добавляет файлы ядра в phar
     * @param Phar $phar
     */
    private function addCoreFiles(Phar $phar)
    {
        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->notName('CoreCompiler.php')
            ->in($this->coreDirectory)
        ;

        foreach ($finder as $file) {
            $this->packFile($phar, $file, $this->obfuscate);
        }
    }

    /**
     * Добавляет вендоров в phar
     * @param Phar $phar
     */
    private function addVendorFiles(Phar $phar)
    {
        $finder = new Finder();
        $finder->files()
            ->name('*.php')
            ->exclude('Tests')
            ->notName('CoreCompiler.php')
            ->in($this->baseDirectory .'/vendor')
        ;

        foreach ($finder as $file) {
            $this->packFile($phar, $file);
        }
    }

    /**
     * Добавляет файл в пакет.
     * @param Phar $phar
     * @param SplFileInfo $file
     * @param bool $obfuscate обфусцировать ли файл
     */
    private function packFile(Phar $phar, SplFileInfo $file, $obfuscate = false) {
        $localPath = strtr(str_replace($this->baseDirectory . '/', '', $file->getRealPath()), '\\', '/');

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

