<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml\object;


use umi\orm\metadata\field\BaseField;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор для свойств объектов.
 */
class FieldSerializer extends BaseSerializer
{
    /**
     * Сериализует поле в XML.
     * @param BaseField $field
     * @param array $options опции сериализации
     */
    public function __invoke(BaseField $field, array $options = [])
    {
        $this->writeElement('field', [
            'type' => $field->getType(),
            'dateType' => '111'
        ]);
    }
}
