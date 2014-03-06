<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml;

/**
 * XML-сериализатор для массивов.
 */
class ArraySerializer extends BaseSerializer
{
    /**
     * @var string $listElementName имя элемента для списков с числовыми ключами
     */
    protected $listElementName = 'item';

    /**
     * Сериализует массив в XML.
     * @param array $array
     * @param array $options опции сериализации
     */
    public function __invoke(array $array, array $options = [])
    {
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = $this->listElementName;
            }
            $this->writeElement($key, [], $value);
        }
    }
}
