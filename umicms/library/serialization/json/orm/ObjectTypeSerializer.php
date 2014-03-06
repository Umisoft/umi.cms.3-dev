<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\orm;

use umi\orm\metadata\field\IField;
use umi\orm\metadata\ObjectType;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для типов коллекции.
 * TODO: move to forms
 */
class ObjectTypeSerializer extends BaseSerializer
{
    /**
     * Сериализует тип в JSON.
     * @param ObjectType $objectType
     * @param array $options опции сериализации
     */
    public function __invoke(ObjectType $objectType, array $options = [])
    {
        $fields = [];
        foreach ($objectType->getFields() as $field) {
            if ($field->getIsReadOnly()) continue;

            $fieldInfo = [
                'name' => $field->getName(),
                'title' => $field->getName(),
                'placeholder' => $field->getName()
            ];

            switch($field->getType()) {
                case IField::TYPE_DATE_TIME: {
                    $fieldInfo['type'] = 'datetime';
                    break;
                }
                case IField::TYPE_BOOL: {
                    $fieldInfo['type'] = 'checkbox';
                    break;
                }
                case IField::TYPE_PASSWORD: {
                    $fieldInfo['type'] = 'password';
                    break;
                }
                case IField::TYPE_TEXT: {
                    $fieldInfo['type'] = 'textarea';
                    break;
                }
                case IField::TYPE_HAS_MANY:
                case IField::TYPE_HAS_ONE:
                case IField::TYPE_MANY_TO_MANY:
                case IField::TYPE_BELONGS_TO: {
                    $fieldInfo['type'] = 'choice';
                    break;
                }
                default:{
                    $fieldInfo['type'] = 'text';
                    break;
                }

            }

            $fields[] = $fieldInfo;
        }

        $this->delegate($fields);
    }
}
 