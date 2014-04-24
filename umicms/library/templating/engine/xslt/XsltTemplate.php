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
use DOMNode;
use DOMXPath;
use umi\templating\exception\RuntimeException;
use umicms\exception\LibXMLException;
use umicms\serialization\ISerializationAware;
use umicms\serialization\TSerializationAware;
use XSLTProcessor;

/**
 * XSLT шаблон.
 */
class XsltTemplate implements ISerializationAware
{
    use TSerializationAware;

    /**
     * @var XsltTemplateEngine $templateEngine
     */
    protected $templateEngine;

    /**
     * Конструктор
     * @param XsltTemplateEngine $templateEngine
     */
    public function __construct(XsltTemplateEngine $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * Запускает XSLT-шаблонизацию.
     * @param string $templateName имя XSL-шаблона
     * @param string $xmlData данные в XML
     * @throws \umi\templating\exception\RuntimeException
     * @return string
     */
    public function render($templateName, $xmlData)
    {
        $templateFilePath = $this->findTemplate($templateName);
        if (!is_readable($templateFilePath)) {
            throw new RuntimeException(sprintf(
                'Cannot render template "%s". XSLT Template file "%s" is not readable.',
                $templateName,
                $templateFilePath
            ));
        }

        $template = $this->createDomDocument(file_get_contents($templateFilePath));
        $this->prepareTemplate($template);
        $template = $this->createDomDocument($template->saveXML());

        $data = $this->createDomDocument($xmlData);

        $xslt = new XSLTProcessor();
        $xslt->registerPHPFunctions();
        $xslt->importStylesheet($template);

        $result = (string) $xslt->transformToXML($data);

        return $result;
    }

    protected function prepareTemplate(DOMDocument $template)
    {
        $xpath = new DOMXPath($template);

        /**
         * @var DOMNode $widgetNode
         */
        foreach ($xpath->query('//umi:widget') as $widgetNode) {
            if ($widgetName = $widgetNode->attributes->getNamedItem('name')) {
                $functionNode = $template->createElement('xsl:apply-templates');
                $function = 'php:function(\'umicms\templating\engine\xslt\XsltTemplateEngine::callWidget\'';
                $function .= ', \'' . $widgetName->nodeValue . '\')/result';
                $functionNode->setAttribute('select', $function);

                $widgetNode->parentNode->replaceChild($functionNode, $widgetNode);
            }

        }

    }
    /**
     * Создает DOMDocument из xml-строки
     * @param string $xmlString
     * @throws LibXMLException если не удалось создать DOMDocument
     * @return DOMDocument
     */
    protected function createDomDocument($xmlString) {
        $document = new DOMDocument('1.0', 'utf-8');
        $document->resolveExternals = true;
        $document->substituteEntities = true;
        $document->formatOutput = true;

        @$document->loadXML($xmlString);

        if ($error = libxml_get_last_error()) {
            libxml_clear_errors();
            throw new LibXMLException($error);
        }

        return $document;
    }

    protected function findTemplate($templateName)
    {
        $directories = $this->templateEngine->getTemplateDirectories();

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
}