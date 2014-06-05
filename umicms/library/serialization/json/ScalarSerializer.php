<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json;

use umicms\serialization\exception\UnexpectedValueException;

/**
 * JSON-сериализатор для скалярных типов.
 */
class ScalarSerializer extends BaseSerializer
{
    /**
     * Сериализует скаляр в JSON.
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

        $this->writeRaw($scalar);
    }
}
