<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\orm;

use umi\orm\metadata\ObjectType;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для типов коллекции.
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
        $this->delegate($objectType->getName());
    }
}
 