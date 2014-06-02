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
