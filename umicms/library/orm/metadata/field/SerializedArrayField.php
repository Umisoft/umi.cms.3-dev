<?php
namespace umicms\orm\metadata\field;

use umi\orm\metadata\field\string\StringField;
use umi\orm\metadata\field\TScalarField;

class SerializedArrayField extends StringField
{
    use TScalarField;

    /**
     * Тип поля
     */
    const TYPE = 'serialized';

    /**
     * {@inheritdoc}
     */
    public function getDataType()
    {
        return self::TYPE;
    }

}
 