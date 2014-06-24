<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\engine\smarty;

use Smarty;
use umi\templating\engine\ITemplateEngine;

/**
 * Smarty шаблонизатор.
 */
class SmartyTemplateEngine implements ITemplateEngine
{
    /**
     * Имя
     */
    const NAME = 'smarty';
    /**
     * Опция для задания директорий расположения шаблонов
     */
    const OPTION_TEMPLATE_DIRECTORIES = 'directories';
    /**
     * Опция для задания расширения файлов шаблонов
     */
    const OPTION_TEMPLATE_FILE_EXTENSION = 'extension';
    /**
     * Опция для задания директории расположения скомпилированных шаблонов
     */
    const OPTION_COMPILE_DIR = 'compileDir';
    /**
     * Опция для задания директории хранения кэша шаблонов
     */
    const OPTION_CACHE_DIR = 'cacheDir';
    /**
     * Опция для задания директории хранения конфигураций Smarty
     */
    const OPTION_CONFIG_DIR = 'configDir';

    /**
     * @var array $options опции
     */
    protected $options = [];
    /**
     * @var Smarty $environment окружение шаблонизатора Smarty
     */
    private $environment;

    /**
     * Устанавливает опции шаблонизатора.
     * @param array $options опции
     * @return self
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Отображает заданный шаблон используя переменные.
     * @param string $templateName имя шаблона
     * @param array $variables переменные
     * @return string отображение
     */
    public function render($templateName, array $variables = [])
    {
        $smarty = $this->getEnvironment();
        $smarty->assign($variables);
        return $smarty->fetch(
            $this->getTemplateFilename($templateName)
        );
    }

    /**
     * Добавляет расширение.
     * @param ISmartyExtension $extension
     * @return $this
     */
    public function addExtension(ISmartyExtension $extension)
    {
        $smarty = $this->getEnvironment();
        foreach ($extension->getFunctions() as $functionName => $function) {
            $smarty->registerPlugin('function', $functionName, $function);
        }

        return $this;
    }

    /**
     * Возвращает конфигурацию Smarty.
     * @return Smarty
     */
    protected function getEnvironment()
    {
        if (!$this->environment) {
            $templateDirectories = isset($this->options[self::OPTION_TEMPLATE_DIRECTORIES]) ? $this->options[self::OPTION_TEMPLATE_DIRECTORIES] : [];

            $this->environment = new Smarty();
            $this->environment
                ->setTemplateDir($templateDirectories)
            // todo: что делать с директориями для скомпилированных шаблонов?
                ->setCompileDir($this->options[self::OPTION_COMPILE_DIR])
                ->setCacheDir($this->options[self::OPTION_CACHE_DIR])
                ->setConfigDir($this->options[self::OPTION_CONFIG_DIR]);
        }

        return $this->environment;
    }

    /**
     * Возрващает имя файла шаблона по имени шаблона.
     * @param string $templateName имя шаблона
     * @return string
     */
    protected function getTemplateFilename($templateName)
    {
        if (isset($this->options[self::OPTION_TEMPLATE_FILE_EXTENSION])) {
            $templateName .= '.' . $this->options[self::OPTION_TEMPLATE_FILE_EXTENSION];
        }

        return $templateName;
    }
}