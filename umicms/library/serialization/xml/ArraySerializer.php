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
