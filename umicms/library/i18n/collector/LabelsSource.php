<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\i18n\collector;

use ReflectionClass;

/**
 * Источник для получения лейблов.
 */
class LabelsSource
{

    /**
     * Имя метода, в котором используются лейблы
     */
    const TRANSLATOR_METHOD = 'translate';

    /**
     * @var array $tokens массив языковых лексем PHP
     */
    protected $tokens = [];
    /**
     * @var string $className имя класса источника
     */
    protected $className;
    /**
     * @var string $namespace неймспейс класса источника
     */
    protected $namespace = '';
    /**
     * @var ReflectionClass $reflectionClass класс
     */
    protected $reflectionClass;

    /**
     * Конструктор
     * @param array $tokens массив языковых лексем PHP
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Возвращает использованные в источнике лейблы
     * @return array
     */
    public function getLabels()
    {
        if (!count($this->tokens)) {
            return [];
        }
        if (!$this->getClassName()) {
            return [];
        }
        $this->detectNamespace();
        if (!$this->checkClassIsLocalizable()) {
            return [];
        }
        return [$this->namespace, $this->detectLabels()];
    }

    /**
     * Возвращает название лексемы и содержимое из ее идентификатора
     * @param mixed $token
     * @return array
     */
    protected function getTokenInfo($token)
    {
        $tokenName = is_array($token) ? $token[0] : null;
        $tokenData = is_array($token) ? $token[1] : $token;
        $tokenData = trim($tokenData);

        return [$tokenName, $tokenData];
    }

    /**
     * Ищет и возвращает использованные среди лексем лейблы
     * @return array
     */
    protected function detectLabels()
    {

        $labels = [];
        $translateDetected = false;

        foreach ($this->tokens as $token) {
            list($tokenName, $tokenData) = $this->getTokenInfo($token);
            if (!$tokenData || !$tokenName) continue;

            if ($tokenData == self::TRANSLATOR_METHOD) {
                $translateDetected = true;
                continue;
            }

            if ($translateDetected && $tokenName == T_CONSTANT_ENCAPSED_STRING) {
                $translateDetected = false;
                $labels[] = $tokenData;
            }
        }

        return $labels;
    }

    /**
     * Ищет неймспейс класса среди лексем
     */
    protected function detectNamespace()
    {
        $namespaceDetected = false;

        foreach ($this->tokens as $token) {
            list($tokenName, $tokenData) = $this->getTokenInfo($token);
            if (!$tokenData) continue;

            if ($tokenName == T_NAMESPACE) {
                $namespaceDetected = true;
                continue;
            }
            if ($namespaceDetected && $tokenData != ';') {
                $this->namespace .= $tokenData;
                continue;
            } elseif ($namespaceDetected && $tokenData == ';') {
                break;
            }
        }
    }

    /**
     * Ищет и возвращает имя класса среди лексем
     * @return string|null
     */
    protected function getClassName()
    {
        $classDetected = false;

        foreach ($this->tokens as $token) {
            list($tokenName, $tokenData) = $this->getTokenInfo($token);
            if (!$tokenData) continue;

            if ($tokenName == T_CLASS) {
                $classDetected = true;
                continue;
            }

            if ($classDetected) {
                $this->className = $tokenData;
                break;
            }
        }

        return $this->className;
    }

    /**
     * Проверяет, реализует ли определенный класс интерфейс ILocalizable
     * @return bool
     */
    protected function checkClassIsLocalizable()
    {
        $classPath = $this->namespace . '\\' . $this->className;

        if (!class_exists($classPath)) {
            return false;
        }

        $this->reflectionClass = new ReflectionClass($classPath);

        if (in_array('umi\i18n\ILocalizable', $this->reflectionClass->getInterfaceNames())) {
            return true;
        }

        if (in_array('umi\i18n\TLocalizable', $this->reflectionClass->getTraitNames())) {
            return true;
        }

        return false;
    }

}

 