<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json;

use umicms\serialization\exception\UnexpectedValueException;

/**
 * JSON-сериализатор для NULL.
 */
class NullSerializer extends BaseSerializer
{
    /**
     * Сериализует null в JSON.
     * @param string $null
     * @param array $options опции сериализации
     * @throws UnexpectedValueException
     */
    public function __invoke($null, array $options = [])
    {
        if (!is_null($null)) {
            throw new UnexpectedValueException($this->translate(
                'Cannot serialize null. Value type "{type}" is not null.',
                ['type' => gettype($null)]
            ));
        }

        $this->writeRaw(null);
    }
}
