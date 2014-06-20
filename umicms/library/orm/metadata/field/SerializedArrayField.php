<?php
namespace umicms\orm\metadata\field;

use umi\orm\metadata\field\BaseField;
use umi\orm\object\IObject;

/**
 * Класс поля для массивов.
 */
class SerializedArrayField extends BaseField
{
    /**
     * Тип поля
     */
    const TYPE = 'serialized';

    /**
     * {@inheritdoc}
     */
    public function getDataType()
    {
        return 'string';
    }

    /**
     * {@inheritdoc}
     */
    public function validateInputPropertyValue($propertyValue)
    {
        return is_array($propertyValue);
    }

    /**
     * @see IField::preparePropertyValue()
     */
    public function preparePropertyValue(IObject $object, $internalDbValue)
    {
        if (is_null($internalDbValue)) {
            return [];
        }

        @settype($internalDbValue, $this->getDataType());

        return unserialize($internalDbValue);
    }

    /**
     * @see IField::prepareDbValue()
     */
    public function prepareDbValue(IObject $object, $propertyValue)
    {
        return serialize($propertyValue);
    }
}
