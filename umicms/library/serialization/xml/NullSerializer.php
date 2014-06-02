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

use umicms\serialization\exception\UnexpectedValueException;

/**
 * XML-сериализатор для NULL.
 */
class NullSerializer extends BaseSerializer
{
    /**
     * Сериализует null в XML.
     * @param string $null
     * @param array $options опции сериализации
     * @throws UnexpectedValueException если передан не скаляр
     */
    public function __invoke($null, array $options = [])
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
