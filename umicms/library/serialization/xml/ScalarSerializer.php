<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml;

use umicms\serialization\exception\UnexpectedValueException;

/**
 * XML-сериализатор для скалярных типов.
 */
class ScalarSerializer extends BaseSerializer
{
    /**
     * Сериализует скаляр в XML.
     * @param string $scalar
     * @param array $options опции сериализации
     * @throws UnexpectedValueException если передан не скаляр
     */
    public function __invoke($scalar, array $options = [])
    {
        if (!is_scalar($scalar)) {
            throw new UnexpectedValueException($this->translate(
                'Cannot serialize scalar. Value type "{type}" is not scalar.',
                ['type' => gettype($scalar)]
            ));
        }

        if (is_bool($scalar)) {
            $scalar = (int) $scalar;
        }

        $this->getXmlWriter()->writeRaw(htmlentities($scalar, ENT_COMPAT, 'utf-8'));
    }
}
