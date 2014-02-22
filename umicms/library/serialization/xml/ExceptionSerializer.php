<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml;

/**
 * XML-сериализатор для исключений.
 */
class ExceptionSerializer extends BaseSerializer
{
    /**
     * Сериализует исключение в XML.
     * @param \Exception $exception
     */
    public function __invoke(\Exception $exception)
    {
        $this->writeAttribute('message', $exception->getMessage());
        $this->writeAttribute('code', $exception->getCode());

    }
}
