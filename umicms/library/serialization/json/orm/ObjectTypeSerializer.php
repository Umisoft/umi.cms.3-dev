<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 