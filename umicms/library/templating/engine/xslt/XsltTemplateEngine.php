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

use umi\stream\IStreamService;
use umi\templating\engine\ITemplateEngine;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\exception\RuntimeException;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;

/**
 * XSLT шаблонизатор.
 */
class XsltTemplateEngine implements ITemplateEngine, ISerializationAware, IToolkitAware
{
    use TSerializationAware;
    use TToolkitAware;

    const NAME = 'xslt';
    /**
     * Имя протокола для вызова виджетов
     */
    const WIDGET_PROTOCOL = 'widget';
    /**
     * Имя протокола для вызова виджетов
     */
    const TEMPLATE_PROTOCOL = 'template';
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

    private static $streamsRegistered = false;

    /**
     * @var array $templateDirectories директории расположения шаблонов
     */
    private $templateDirectories;

    public function __construct()
    {
        if (!self::$streamsRegistered) {
            /**
             * @var IStreamService $streams
             */
            $streams = $this->getToolkit()->getService('umi\stream\IStreamService');
            /**
             * @var CmsDispatcher $dispatcher
             */
            $dispatcher = $this->getToolkit()->getService('umi\hmvc\dispatcher\IDispatcher');
            $streams->registerStream(
                self::WIDGET_PROTOCOL, function($uri) use ($dispatcher) {

                    $widgetInfo = parse_url($uri);
                    $widgetParams = [];
                    if (isset($widgetInfo['query'])) {
                        parse_str($widgetInfo['query'], $widgetParams);
                    }

                    return $this->serializeResult(ISerializerFactory::TYPE_XML, [
                            'result' => $dispatcher->executeWidgetByPath($widgetInfo['host'], $widgetParams)
                        ]
                    );
                }
            );

            $streams->registerStream(
                self::TEMPLATE_PROTOCOL, function($uri) {

                    $filePathInfo = parse_url($uri);
                    $filePath = (isset($filePathInfo['path'])) ? $filePathInfo['host'] . $filePathInfo['path'] : $filePathInfo['host'];
                    return file_get_contents($this->findTemplate($this->getTemplateFilename($filePath)));
                }
            );

            self::$streamsRegistered = true;
        }
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
                $this->serializeVariablesToXml($variables)
            );
    }

    public function findTemplate($templateName)
    {
        $directories = $this->getTemplateDirectories();

        foreach($directories as $directory) {
            $templateFilePath = $directory . DIRECTORY_SEPARATOR . $templateName;
            if (is_file($templateFilePath)) {
                return $templateFilePath;
            }
        }

        throw new RuntimeException(
            sprintf('Unable to find template "%s" (looked into: %s).', $templateName, implode(', ', $directories))
        );
    }

    /**
     * Возвращает директории расположения шаблонов.
     * @return array
     */
    protected function getTemplateDirectories()
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

    /**
     * Сериализует результат в указанный формат
     * @param string $format формат
     * @param mixed $variables список переменных
     * @return string
     */
    protected function serializeResult($format, $variables) {
        $serializer = $this->getSerializer($format, $variables);
        $serializer->init();
        $serializer($variables);

        return $serializer->output();
    }
}