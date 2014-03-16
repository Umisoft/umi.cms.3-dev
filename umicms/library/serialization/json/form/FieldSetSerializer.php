<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\form;

use umi\form\fieldset\FieldSet;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для набора сущностей формы.
 */
class FieldSetSerializer extends BaseSerializer
{
    /**
     * Сериализует набор сущностей формы в JSON.
     * @param FieldSet $fieldSet
     * @param array $options опции сериализации
     */
    public function __invoke(FieldSet $fieldSet, array $options = [])
    {

        $result = [
            'type' => $fieldSet::TYPE_NAME
        ];

        if ($name = $fieldSet->getName()) {
            $result['name'] = $name;
        }

        if ($attributes = $fieldSet->getAttributes()) {
            $result['attributes'] = $attributes;
        }

        if ($elements = iterator_to_array($fieldSet, false)) {
            $result['elements'] = $elements;
        }

        $this->delegate($result);
    }
}
 