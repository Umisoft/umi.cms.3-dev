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
use umicms\serialization\ISerializerConfigurator;

/**
 * JSON-сериализатор для произвольных объектов "по умолчанию".
 */
class ObjectSerializer extends BaseSerializer
{
    /**
     * Сериализует объект в JSON.
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

        if ($object instanceof \Traversable) {
            $variables = iterator_to_array($object, true);
        } else {
            $variables = get_object_vars($object);
        }

        if ($object instanceof ISerializerConfigurator) {
            $this->configure($object);
        }

        $result = [];

        foreach ($variables as $name => $value) {
            if (in_array($name, $this->currentExcludes)) {
                continue;
            }
            $result[$name] = $value;
        }

        $this->delegate($result, $options);
    }
}
