<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml;

use umicms\exception\LibXMLException;
use umicms\project\Environment;

/**
 * XML-сериализатор для исключений, связанных с ошибками XML-библиотеки
 */
class LibXMLExceptionSerializer extends BaseSerializer
{
    /**
     * Сериализует исключение в XML.
     * @param LibXMLException $exception
     * @param array $options опции сериализации
     */
    public function __invoke(LibXMLException $exception, array $options = [])
    {
        $this->writeAttribute('message', $exception->getMessage());
        $this->writeAttribute('code', $exception->getCode());

        if (Environment::$showExceptionTrace) {
            $this->getXmlWriter()->startElement('trace');
            $this->delegate($exception->getTraceAsString(), $options);
            $this->getXmlWriter()->endElement();
        }

        $this->getXmlWriter()->startElement('libXMLErrors');
        $this->delegate($exception->getErrors(), $options);
        $this->getXmlWriter()->endElement();
    }
}
 