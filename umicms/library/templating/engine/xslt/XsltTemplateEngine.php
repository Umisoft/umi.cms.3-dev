<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\templating\engine\xslt;

use DOMDocument;
use umi\hmvc\dispatcher\IDispatcher;
use umi\templating\engine\ITemplateEngine;
use umicms\hmvc\dispatcher\Dispatcher;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializer;
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
     * @var Dispatcher $dispatcher
     */
    private static $dispatcher;

    /**
     * @var ISerializer $dispatcher
     */
    private static $serializer;

    /**
     * @param IDispatcher $dispatcher
     */
    public function __construct(IDispatcher $dispatcher)
    {
        self::$dispatcher = $dispatcher;
        self::$serializer = $this->getSerializer(ISerializerFactory::TYPE_XML, []);
    }

    public static function callWidget($widgetPath, $queryString = "")
    {
        parse_str($queryString, $params);

        $variables = self::$dispatcher->executeWidgetByPath($widgetPath, $params);
        $xml = self::serializeVariablesToXml(['contents' => $variables]);
        $dom = new DOMDocument('1.0', 'utf8');
        $dom->loadXML($xml);

        return $dom;
    }

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
                self::serializeVariablesToXml($variables)
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
     * @param $variables
     * @return string
     */
    protected static function serializeVariablesToXml($variables) {
        $result = ['result' => $variables];

        $serializer = self::$serializer;
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