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
