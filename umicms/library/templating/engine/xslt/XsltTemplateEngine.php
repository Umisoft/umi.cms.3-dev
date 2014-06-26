<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\engine\xslt;

use umi\templating\engine\ITemplateEngine;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;

/**
 * XSLT шаблонизатор.
 */
class XsltTemplateEngine implements ITemplateEngine, ISerializationAware
{
    use TSerializationAware;

    const NAME = 'xslt';
    /**
     * Опция для задания директорий расположения шаблонов
     */
    const OPTION_TEMPLATE_DIRECTORIES = 'directories';
    /**
     * Опция для задания расширения файлов шаблонов
     */
    const OPTION_TEMPLATE_FILE_EXTENSION = 'extension';

    /**
     * @var array $options опции
     */
    protected $options = [];

    /**
     * @var callable[] $functions
     */
    protected $functions = [];

    /**
     * @var array $templateDirectories директории расположения шаблонов
     */
    private $templateDirectories;

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render($templateFile, array $variables = [])
    {
        return (new XsltTemplate($this))
            ->render(
                $this->getTemplateFilename($templateFile),
                $this->serializeVariablesToXml($variables)
            );
    }

    /**
     * Возвращает директории расположения шаблонов.
     * @return array
     */
    public function getTemplateDirectories()
    {
        if (is_null($this->templateDirectories)) {
            $this->templateDirectories = isset($this->options[self::OPTION_TEMPLATE_DIRECTORIES]) ? $this->options[self::OPTION_TEMPLATE_DIRECTORIES] : [];
        }

        return (array) $this->templateDirectories;
    }

    /**
     * Сериализует переменные в xml
     * @param array $variables
     * @return string
     */
    protected function serializeVariablesToXml(array $variables) {
        $result = ['layout' => $variables];

        $serializer = $this->getSerializer(ISerializerFactory::TYPE_XML, $result);
        $serializer->init();
        $serializer($result);

        return $serializer->output();
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