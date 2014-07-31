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
use umicms\serialization\ISerializerConfigurator;

/**
 * XML-сериализатор для произвольных объектов "по умолчанию".
 */
class ObjectSerializer extends BaseSerializer
{
    /**
     * Сериализует объект в XML.
     * @param object $object
     * @param array $options опции сериализации
     * @throws UnexpectedValueException если передан не объект
     */
    public function __invoke($object, array $options = [])
    {
        if (!is_object($object)) {
            throw new UnexpectedValueException($this->translate(
                'Cannot serialize object. Value type "{type}" is not object.',
                ['type' => gettype($object)]
            ));
        }

        if ($object instanceof ISerializerConfigurator) {
            $this->configure($object);
        }

        if ($object instanceof \Traversable) {
            $variables = iterator_to_array($object, true);
        } else {
            $variables = get_object_vars($object);
        }

        $this->delegate($variables, $options);
    }
}
