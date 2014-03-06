<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json;

/**
 * JSON-сериализатор для массивов.
 */
class ArraySerializer extends BaseSerializer
{
    /**
     * Сериализует массив в JSON.
     * @param array $array
     * @param array $options опции сериализации
     */
    public function __invoke(array $array, array $options = [])
    {
        if (!empty($array)) {
            foreach ($array as $key => $value) {

                $this->getJsonWriter()
                    ->startElement($key);

                $this->delegate($value, $options);

                $this->getJsonWriter()
                    ->endElement();
            }
        } else {
            $this->writeRaw([]);
        }
    }
}
