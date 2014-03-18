<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\orm;

use umi\orm\metadata\Metadata;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для метаданных коллекции.
 */
class MetadataSerializer extends BaseSerializer
{
    /**
     * Сериализует метаданные в JSON.
     * @param Metadata $metadata
     * @param array $options опции сериализации
     */
    public function __invoke(Metadata $metadata, array $options = [])
    {
        $this->getJsonWriter()->startElement('fields');
        $this->delegate(array_values($metadata->getFields()), $options);
        $this->getJsonWriter()->endElement();
    }
}
 