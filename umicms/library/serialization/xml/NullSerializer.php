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
 * XML-сериализатор для NULL.
 */
class NullSerializer extends BaseSerializer
{
    /**
     * Сериализует null в XML.
     * @param string $null
     * @throws UnexpectedValueException если передан не скаляр
     */
    public function __invoke($null)
    {
        if (!is_null($null)) {
            throw new UnexpectedValueException($this->translate(
                'Cannot serialize null. Value type "{type}" is not null.',
                ['type' => gettype($null)]
            ));
        }

        $this->getXmlWriter()->writeRaw('');
    }
}
